import React from 'react';

function ItemRadio({ item }) {
    const valueList = item.value_list ?? {};

    // 形式: { 0: { name: "SNS", count: null }, ... }
    const getChoices = () => {
        if (!valueList || Object.keys(valueList).length === 0) {
            return [];
        }
        // インデックス順にソートしてnameを取得
        return Object.entries(valueList)
            .sort(([a], [b]) => parseInt(a) - parseInt(b))
            .map(([_, item]) => ({
                name: item.name,
                count: item.count ?? null
            }));
    };

    const choices = getChoices();

    return (
        <div className="flex gap-4">
            {choices.length === 0 && (
                <div className="text-sm text-gray-400">
                    未選択
                </div>
            )}

            {choices.map((choice, index) => (
                <div key={`${choice.name}-${index}`} className="text-sm text-gray-800">
                    ◎ {choice.name}
                </div>
            ))}
        </div>
    );
}

export default ItemRadio;
