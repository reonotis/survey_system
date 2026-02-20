import { useState, useMemo } from 'react';

export function useDraftFormItems(initialItems = []) {

    /** 設定中の項目一覧 */
    const [draftFormItems, setDraftFormItems] = useState(initialItems);

    /** 追加可能な項目を計算する */
    const createAddableItems = () => {
        // item_type ごとの使用数をカウント
        const usedCountByType = draftFormItems.reduce((acc, item) => {
            const type = String(item.item_type);
            acc[type] = (acc[type] || 0) + 1;
            return acc;
        }, {});

        const allItemsEnum = Array.isArray(window.allFormItemListEnum)
            ? window.allFormItemListEnum
            : [];

        // enum 情報 (value / label) を元に、追加可能な item_type のみ返す
        return allItemsEnum
            .filter(({ value }) => {
                const type = String(value);
                const limit = window.upperLimitItemType[type];

                // 上限未定義なら無制限として許可
                if (limit === undefined) {
                    return true;
                }

                const usedCount = usedCountByType[type] || 0;
                return usedCount < limit;
            })
            .map(({ value, label }) => {
                const type = String(value);
                return {
                    itemType: type,
                    label,
                };
            });
    };

    /** 追加可能な項目一覧 */
    const addableItems = useMemo(() => {
        return createAddableItems();
    }, [draftFormItems]);

    /** 項目を追加した時の処理 */
    const handleAddItem = (newItem) => {
        setDraftFormItems(prev => [...prev, newItem]);
    };

    /** 項目の並び替えを行った時に発火される */
    const handleOrderChange = async (sortedItems) => {
        // 並び順を state に反映
        setDraftFormItems(sortedItems);

        try {
            const response = await fetch(window.draftSortChangeUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': window.csrfToken,
                },
                body: JSON.stringify({
                    item_ids: sortedItems.map(item => item.id),
                }),
            });

        } catch (error) {
            console.error('並び替え処理失敗', error);
            alert('並び替え処理に失敗しました。画面を再読み込みします。');
            window.location.reload();
        }
    };

    /** 編集中の項目 */
    const [editingItemId, setEditingItemId] = useState(null);

    /** 選択した編集中の項目 */
    const selectedItem = draftFormItems.find(
        item => item.id === editingItemId
    );

    /** 対象項目の特定カラムの値を変更する */
    const updateItemLocalValue = (itemId, key, value) => {
        setDraftFormItems(prev =>
            prev.map(item =>
                item.id === itemId
                    ? { ...item, [key]: value }
                    : item
            )
        );
    };

    /** 変更している対象項目の特定カラムの値を変更する */
    const saveItemValue = async (itemId, key, value) => {
        // 対象アイテムを取得
        const targetItem = draftFormItems.find(item => item.id === itemId);
        if (!targetItem) {
            console.warn('対象アイテムが見つかりません', itemId);
            return;
        }

        console.log(value)
        // 現在の値を保存する
        try {
            const response = await fetch(window.draftItemSaveUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': window.csrfToken,
                },
                body: JSON.stringify({
                    item_id: itemId,
                    key: key,
                    value: value,
                }),
            });

        } catch (error) {
            console.error('更新処理失敗', error);
            alert('更新処理に失敗しました。画面を再読み込みします。');
            window.location.reload();
        }
    };

    /** 対象の項目を削除する */
    const itemDelete = async (itemId) => {
        // ローカルのデータから削除しておく
        setDraftFormItems(prevItems => {
            return prevItems.filter(item => item.id !== itemId);
        });

        // 削除のリクエストを送信する
        try {
            const response = await fetch(window.draftItemDeleteUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': window.csrfToken,
                },
                body: JSON.stringify({
                    item_id: itemId,
                }),
            });

        } catch (error) {
            console.error('削除処理失敗', error);
            alert('削除処理に失敗しました。画面を再読み込みします。');
            window.location.reload();
        }
    };


    return {
        draftFormItems,
        addableItems,
        handleAddItem,
        handleOrderChange,
        setEditingItemId,
        selectedItem,
        updateItemLocalValue,
        saveItemValue,
        itemDelete,
    };
}

