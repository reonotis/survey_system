import React from 'react';
import ItemTypeName from './ItemTypeName';
import ItemTypeKana from './ItemTypeKana';
import ItemTypeEmail from './ItemTypeEmail';
import ItemTypeDefault from './ItemTypeDefault';

function ItemDetailPanel({ selectedItem, itemTypeList }) {
    if (!selectedItem) {
        return (
            <div>
                <h3 className="title-label">詳細設定</h3>
                <p className="text-sm text-gray-500 text-center py-8">
                    項目を選択してください
                </p>
            </div>
        );
    }

    const itemType = selectedItem.item_type;

    const renderDetailContent = () => {
        switch (itemType) {
            case 1: // お名前
                return <ItemTypeName itemTypeList={itemTypeList} selectedItem={selectedItem}/>;
            case 2: // ヨミ
                return <ItemTypeKana itemTypeList={itemTypeList} selectedItem={selectedItem}/>;
            case 3: // メール
                return <ItemTypeEmail itemTypeList={itemTypeList} itemType={itemType} selectedId={selectedItem.id}/>;
            case 4: // 電話
            case 5: // 性別
            case 6: // 住所
            default:
                return "";
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

