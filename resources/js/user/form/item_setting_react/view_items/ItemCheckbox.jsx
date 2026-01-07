import React from 'react';

function ItemCheckbox({ item }) {
    const valueList = item.value_list ?? {};

    return (
        <div className="flex gap-4">
            {valueList.length === 0 && (
                <div className="text-sm text-gray-400">
                    未選択
                </div>
            )}

            {Object.entries(valueList).map(([label, value], index) => (
                <div key={`${label}-${index}`} className="text-sm text-gray-800">
                    □ {label}
                </div>
            ))}
        </div>
    );
}

export default ItemCheckbox;
