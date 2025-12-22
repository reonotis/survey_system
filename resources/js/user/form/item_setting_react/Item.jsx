import React from 'react';
import { useSortable } from '@dnd-kit/sortable';
import { CSS } from '@dnd-kit/utilities';
import ItemName from './view_items/ItemName.jsx';

function Item({ item, onClick, itemDelete }) {
    const itemTypeList = window.itemTypeList;

    const baseItemName = itemTypeList[item.item_type];
    const itemTitle = item.item_title;

    const {
        attributes,
        listeners,
        setNodeRef,
        transform,
        transition,
    } = useSortable({ id: item.id });

    const style = {
        transform: CSS.Transform.toString(transform),
        transition,
    };

    const renderDetailContent = () => {
        switch (item.item_type) {
            case 1:
                return <ItemName item={item} />;
            case 2:
                return 'フリガナ';
            case 3:
                return 'メール';
            case 4:
                return '電話';
            case 5:
                return '性別';
            case 6:
                return '住所';
            default:
                return '詳細が入ります';
        }
    };

    return (
        <div
            ref={setNodeRef}
            style={style}
            className="p-3 border border-gray-300 rounded bg-white cursor-pointer"
            onClick={onClick}
        >
            <div className="flex justify-between  w-full border-b-2 border-gray-300 items-center ">
                <div className="flex items-center">
                    {/* ハンドル */}
                    <div
                        className="p-2 cursor-move select-none text-sm text-gray-600"
                        {...attributes}
                        {...listeners}
                    >
                        ☰
                    </div>

                    <p className="text-sm font-medium text-gray-700">
                        {itemTitle ?? baseItemName}<span className="pl-2 font-normal">({baseItemName})</span>
                    </p>

                    {item.field_required === 1 && (
                        <span className="ml-2 px-1.5 py-0.5 text-xs rounded bg-red-100 text-red-600">必須</span>
                    )}
                </div>
                <button
                    type="button"
                    className="text-xs text-red-600 hover:text-red-800 hover:underline"
                    onClick={(e) => {
                        e.stopPropagation(); // ★ 親の onClick / DnD 防止

                        const ok = window.confirm('この項目を削除しますか？');
                        if (!ok) return;

                        itemDelete(item.id);
                    }}
                >
                    消去する
                </button>
            </div>

            <div className="mt-1 text-xs text-gray-400 whitespace-pre-line">
                {item.annotation_text}
            </div>

            <div className="mt-2 text-sm text-gray-600">
                {renderDetailContent()}
            </div>
        </div>
    );
}

export default Item;
