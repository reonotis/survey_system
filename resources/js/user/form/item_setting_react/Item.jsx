import React from 'react';
import { useSortable } from '@dnd-kit/sortable';
import { CSS } from '@dnd-kit/utilities';
import ItemAddress from './view_items/ItemAddress.jsx';
import ItemEmail from './view_items/ItemEmail.jsx';
import ItemGender from './view_items/ItemGender.jsx';
import ItemKana from './view_items/ItemKana.jsx';
import ItemName from './view_items/ItemName.jsx';
import ItemPrecautions from './view_items/ItemPrecautions.jsx';
import ItemTel from './view_items/ItemTel.jsx';
import ItemTerms from './view_items/ItemTerms.jsx';

function Item({ item, onClick, itemDelete, isSelected }) {
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
                return <ItemKana item={item} />;
            case 3:
                return <ItemEmail item={item} />;
            case 4:
                return <ItemTel item={item} />;
            case 5:
                return <ItemGender item={item} />;
            case 6:
                return <ItemAddress item={item} />;
            case 51: // 利用規約
                return <ItemTerms item={item} />;
            case 52: // 注意事項
                return <ItemPrecautions item={item} />;
            default:
                return '詳細が入ります';
        }
    };

    return (
        <div
            ref={setNodeRef}
            style={style}
            className={`border rounded bg-white cursor-pointer transition
                    ${isSelected
                            ? 'border-blue-500 ring-2 ring-blue-200'
                            : 'border-gray-300'
                        }
            `}
            onClick={onClick}
        >
            <div className="flex justify-between w-full items-center bg-gray-200 px-2">
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
                    className="text-xs text-red-600 hover:text-red-800 hover:underline mr-2"
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

            <div className="p-2">
                <div className="text-xs text-gray-400 whitespace-pre-line">
                    {item.annotation_text}
                </div>

                <div className="mt-1 text-sm text-gray-600">
                    {renderDetailContent()}
                </div>
            </div>
        </div>
    );
}

export default Item;
