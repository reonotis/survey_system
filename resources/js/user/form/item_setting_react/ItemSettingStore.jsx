import React, { useState, useEffect } from 'react';

// シンプルなシングルトンストア（Pinia 風）
let storeState = {
    formItems: [],
    selectedItem: null,
    itemTypeList: {},
    commonConsts: {},
    registerFormItemUrl: '',
    updateItemOrderUrl: '',
    csrfToken: '',
};

const listeners = new Set();

function setState(patch) {
    storeState = { ...storeState, ...patch };
    listeners.forEach((listener) => listener());
}

// デバッグ用オブジェクトを最初から設定（initItemSettingStore が呼ばれる前でも使えるように）
if (typeof window !== 'undefined') {
    window.itemSettingDebug = {
        getState: () => storeState,
    };
}

export function initItemSettingStore(initialState) {
    // setState を使ってリスナーをトリガーし、既にマウントされたコンポーネントを再レンダリングさせる
    setState(initialState);
}

export function useItemSettingStore() {
    // storeState の現在の値を状態として保持
    const [state, setStateLocal] = useState(() => ({ ...storeState }));

    useEffect(() => {
        // 初回マウント時にも最新の値を取得
        setStateLocal({ ...storeState });

        const listener = () => {
            setStateLocal({ ...storeState });
        };
        listeners.add(listener);
        return () => {
            listeners.delete(listener);
        };
    }, []);

    const {
        formItems,
        selectedItem,
        itemTypeList,
        commonConsts,
        registerFormItemUrl,
        updateItemOrderUrl,
        csrfToken,
    } = state;

    const handleItemClick = (item) => {
        setState({ selectedItem: item });
    };

    const handleDropItem = async (itemType, insertIndex) => {
        try {
            const formData = new FormData();
            formData.append('_token', storeState.csrfToken);
            formData.append('new_item_type', itemType);
            formData.append('required', '0');
            formData.append('item_title', storeState.itemTypeList[itemType] || '');
            formData.append('insert_index', insertIndex);

            const response = await fetch(storeState.registerFormItemUrl, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                },
            });

            const responseData = await response.json();
            if (!response.ok || responseData.success === false) {
                alert('項目の追加に失敗しました: ' + (responseData.message || '不明なエラー'));
                return;
            }

            if (responseData.form_item) {
                const newItem = responseData.form_item;
                const currentItems = storeState.formItems;
                const updatedItems = currentItems.map((item) => {
                    if (item.sort >= insertIndex + 1) {
                        return { ...item, sort: item.sort + 1 };
                    }
                    return item;
                });
                updatedItems.push(newItem);
                updatedItems.sort((a, b) => a.sort - b.sort);
                setState({ formItems: updatedItems });
            }
        } catch (error) {
            console.error('項目追加エラー:', error);
            alert('項目の追加中にエラーが発生しました');
        }
    };

    const saveItem = async (itemId, payload) => {
        const currentItems = storeState.formItems;
        const targetItem = currentItems.find((item) => item.id === itemId);
        if (!targetItem || !targetItem.update_url) {
            console.warn('更新先URLが見つかりません', targetItem);
            return;
        }

        try {
            const formData = new FormData();
            formData.append('_token', storeState.csrfToken);
            formData.append('item_type', targetItem.item_type);

            Object.entries(payload).forEach(([key, value]) => {
                if (value === undefined || value === null) return;
                formData.append(key, value);
            });

            const response = await fetch(targetItem.update_url, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    Accept: 'application/json',
                },
            });

            const data = await response.json();
            if (!response.ok || data.success === false) {
                alert('項目の保存に失敗しました: ' + (data.message || '不明なエラー'));
                return;
            }

            if (data.form_item) {
                const updatedItems = currentItems.map((item) =>
                    item.id === itemId ? { ...item, ...data.form_item } : item,
                );
                const currentSelected = storeState.selectedItem;
                setState({
                    formItems: updatedItems,
                    selectedItem:
                        currentSelected && currentSelected.id === itemId
                            ? { ...currentSelected, ...data.form_item }
                            : currentSelected,
                });
            }
        } catch (error) {
            console.error('項目更新エラー:', error);
            alert('項目の更新中にエラーが発生しました');
        }
    };

    return {
        formItems,
        selectedItem,
        itemTypeList,
        commonConsts,
        registerFormItemUrl,
        updateItemOrderUrl,
        csrfToken,
        handleItemClick,
        handleDropItem,
        saveItem,
    };
}


