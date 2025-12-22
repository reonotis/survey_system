import React from 'react';
import ItemTypeName from './detail_items/ItemTypeName';
import ItemTypeKana from './detail_items/ItemTypeKana';


function ItemDetailPanel({ selectedItem, updateItemLocalValue, saveItemValue }) {
    if (!selectedItem) {
        return (
            <div>
                <h3 className="title-label">詳細設定</h3>
                <p className="text-sm text-gray-500 text-center py-8">
                    編集する項目を選択してください
                </p>
            </div>
        );
    }


    const renderDetailContent = () => {
        switch (selectedItem.item_type) {
            case 1:
                return <ItemTypeName selectedItem={selectedItem} updateItemLocalValue={updateItemLocalValue} saveItemValue={saveItemValue} />;
            case 2:
                return <ItemTypeKana selectedItem={selectedItem} updateItemLocalValue={updateItemLocalValue} saveItemValue={saveItemValue} />;
            case 3:
                return 'メール';
            case 4:
                return '電話';
            case 5:
                return '性別';
            case 6:
                return '住所';
            default:
                return '';
        }
    };

    return (
        <div>
            <h3 className="title-label">詳細設定</h3>
            <div className="space-y-4">
                {renderDetailContent()}
            </div>
        </div>
    );
}

export default ItemDetailPanel;

