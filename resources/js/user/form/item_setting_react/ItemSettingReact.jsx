import React, { useState, useEffect } from 'react';
import { createRoot } from 'react-dom/client';
import SelectableItemsList from './SelectableItemsList';
import CurrentItemsList from './CurrentItemsList';
import ItemDetailPanel from './ItemDetailPanel';
import {
    initItemSettingData,
    updateFormItems,
    updateSelectedItem,
    setSaveItemFunction,
    getFormItems,
    getCsrfToken,
} from './itemSettingData';

function ItemSetting({
    selectableItems,
    initialFormItems,
    itemTypeList,
    commonConsts,
    registerFormItemUrl,
    updateItemOrderUrl,
    csrfToken,
}) {
    // React state（コンポーネント内のローカル状態）
    const [selectedItemId, setSelectedItemId] = useState(null);

    // 配列をidをキーにしたオブジェクトに変換
    const arrayToObject = (items) => {
        if (!Array.isArray(items)) return {};
        return items.reduce((acc, item) => {
            acc[item.id] = item;
            return acc;
        }, {});
    };

    const [formItemsList, setFormItemsList] = useState(() => arrayToObject(initialFormItems || []));

    // 選択中の項目（右側に表示する項目）
    const selectedItem = selectedItemId ? formItemsList[selectedItemId] : null;

    // 初期化時にデータストアに保存（配列形式で保存）
    useEffect(() => {
        const itemsArray = Object.values(formItemsList);
        initItemSettingData({
            formItems: itemsArray,
            itemTypeList,
            commonConsts,
            registerFormItemUrl,
            updateItemOrderUrl,
            csrfToken,
        });
    }, []); // 初回マウント時のみ実行

    // formItemsList が更新されたらデータストアも更新（配列形式で保存）
    useEffect(() => {
        const itemsArray = Object.values(formItemsList);
        updateFormItems(itemsArray);
    }, [formItemsList]);

    // selectedItem が更新されたらデータストアも更新
    useEffect(() => {
        updateSelectedItem(selectedItem);
    }, [selectedItem]);

    const handleItemClick = (itemId) => {
        setSelectedItemId(itemId);
    };

    // 左側から真ん中に新しい項目を追加したときの処理
    const handleDropItem = async (itemType, insertIndex) => {
        try {
            const formData = new FormData();
            formData.append('_token', csrfToken);
            formData.append('new_item_type', itemType);
            formData.append('required', '0');
            formData.append('item_title', itemTypeList[itemType] || '');
            formData.append('insert_index', insertIndex);

            const response = await fetch(registerFormItemUrl, {
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
                // オブジェクトを配列に変換して処理
                const itemsArray = Object.values(formItemsList);
                const updatedItems = itemsArray.map((item) => {
                    if (item.sort >= insertIndex + 1) {
                        return { ...item, sort: item.sort + 1 };
                    }
                    return item;
                });
                updatedItems.push(newItem);
                updatedItems.sort((a, b) => a.sort - b.sort);
                // 配列をオブジェクトに変換して保存
                setFormItemsList(arrayToObject(updatedItems));
            }
        } catch (error) {
            console.error('項目追加エラー:', error);
            alert('項目の追加中にエラーが発生しました');
        }

        console.log(formItemsList)
    };

    const handleSaveItem = async (itemId, payload) => {
        // itemSettingData.js から最新のデータを取得（配列形式）
        const currentFormItems = getFormItems();
        const currentCsrfToken = getCsrfToken();

        // 配列形式の場合はfind
        const targetItem = Array.isArray(currentFormItems)
            ? currentFormItems.find((item) => item.id === itemId)
            : currentFormItems[itemId];
        if (!targetItem || !targetItem.update_url) {
            console.warn('更新先URLが見つかりません', targetItem);
            return;
        }

        try {
            const formData = new FormData();
            formData.append('_token', currentCsrfToken);
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
                // データストアから最新のデータを取得して更新
                const latestFormItems = getFormItems();
                const itemsArray = Array.isArray(latestFormItems)
                    ? latestFormItems
                    : Object.values(latestFormItems);

                // 更新された項目で置き換え
                const updatedItemsArray = itemsArray.map(item =>
                    item.id === itemId ? { ...item, ...data.form_item } : item
                );

                // オブジェクト形式に変換して保存
                const updatedItems = arrayToObject(updatedItemsArray);
                setFormItemsList(updatedItems);
            }
        } catch (error) {
            console.error('項目更新エラー:', error);
            alert('項目の更新中にエラーが発生しました');
        }
    };

    // 保存処理をデータストアに登録
    useEffect(() => {
        setSaveItemFunction(handleSaveItem);
    }, []); // 初回のみ登録（handleSaveItem は常に最新のデータを取得するため）

    return (
        <div className="flex gap-2 mt-4">
            {/* 左側：追加可能項目 */}
            <div className="bg-white rounded-lg shadow px-2" style={{ width: '250px' }}>
                <SelectableItemsList selectableItems={selectableItems} />
            </div>

            {/* 中央：現在設定中の項目 */}
            <div className="bg-white rounded-lg shadow p-2" style={{ flex: '1' }}>
                <CurrentItemsList
                    formItemsList={formItemsList}
                    itemTypeList={itemTypeList}
                    onItemClick={handleItemClick}
                    selectedItemId={selectedItemId}
                    onDropItem={handleDropItem}
                />
            </div>

            {/* 右側：詳細設定 */}
            <div className="bg-white rounded-lg shadow px-2" style={{ width: '600px' }}>
                <ItemDetailPanel
                    selectedItem={selectedItem}
                    itemTypeList={itemTypeList}
                />
            </div>
        </div>
    );
}

function mountReactComponent() {
    const container = document.getElementById('react-item-setting-container');

    if (!container) {
        console.warn('[React Mount] Container not found, retrying...');
        return false;
    }

    const selectableItems = window.selectableItems || {};
    const formItemsList = window.formItemsList || [];
    const itemTypeList = window.itemTypeList || {};
    const commonConsts = window.commonConsts || {};
    const registerFormItemUrl = window.registerFormItemUrl || '';
    const updateItemOrderUrl = window.updateItemOrderUrl || '';
    const csrfToken = window.csrfToken || '';

    const root = createRoot(container);
    root.render(
        <ItemSetting
            selectableItems={selectableItems}
            initialFormItems={formItemsList}
            itemTypeList={itemTypeList}
            commonConsts={commonConsts}
            registerFormItemUrl={registerFormItemUrl}
            updateItemOrderUrl={updateItemOrderUrl}
            csrfToken={csrfToken}
        />
    );
    return true;
}

// DOMContentLoadedまたはwindow.loadイベントでマウントを試みる
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        console.log('[React Mount] DOMContentLoaded fired');
        // 少し待ってからマウント（windowオブジェクトの設定を待つ）
        setTimeout(() => {
            if (!mountReactComponent()) {
                // 失敗した場合は再試行
                setTimeout(mountReactComponent, 100);
            }
        }, 100);
    });
} else {
    // すでにDOMが読み込まれている場合
    setTimeout(() => {
        if (!mountReactComponent()) {
            setTimeout(mountReactComponent, 100);
        }
    }, 100);
}
