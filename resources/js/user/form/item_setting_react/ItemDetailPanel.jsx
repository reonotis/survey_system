import React from 'react';
import ItemTypeAddress from './detail_items/ItemTypeAddress';
import ItemTypeEmail from './detail_items/ItemTypeEmail';
import ItemTypeGender from './detail_items/ItemTypeGender';
import ItemTypeKana from './detail_items/ItemTypeKana';
import ItemTypeName from './detail_items/ItemTypeName';
import ItemTypePrecautions from './detail_items/ItemTypePrecautions';
import ItemTypeTel from './detail_items/ItemTypeTel';
import ItemTypeTerms from './detail_items/ItemTypeTerms';

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
                return <ItemTypeEmail selectedItem={selectedItem} updateItemLocalValue={updateItemLocalValue} saveItemValue={saveItemValue} />;
            case 4:
                return <ItemTypeTel selectedItem={selectedItem} updateItemLocalValue={updateItemLocalValue} saveItemValue={saveItemValue} />;
            case 5:
                return <ItemTypeGender selectedItem={selectedItem} updateItemLocalValue={updateItemLocalValue} saveItemValue={saveItemValue} />;
            case 6:
                return <ItemTypeAddress selectedItem={selectedItem} updateItemLocalValue={updateItemLocalValue} saveItemValue={saveItemValue} />;
            case 51: // 利用規約
                return <ItemTypeTerms selectedItem={selectedItem} updateItemLocalValue={updateItemLocalValue} saveItemValue={saveItemValue} />;
            case 52: // 注意事項
                return <ItemTypePrecautions selectedItem={selectedItem} updateItemLocalValue={updateItemLocalValue} saveItemValue={saveItemValue} />;
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

