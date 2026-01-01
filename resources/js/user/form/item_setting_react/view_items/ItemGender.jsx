import React from 'react';

function ItemGender({ item }) {
    // 選択されている gender の配列（例: [2, 1]）
    const genderList = item.details.gender_list || [];

    const genderConst = window.commonConst.GENDER_LIST || {};

    return (
        <div className="flex gap-4">
            {genderList.length === 0 && (
                <div className="text-sm text-gray-400">
                    未選択
                </div>
            )}

            {Object.entries(genderConst).map(([value, label]) => {

                const intValue = Number(value); // ★ ここ
                if (!genderList.includes(intValue)) return null;

                return (
                    <div
                        key={value}
                        className="text-sm text-gray-800"
                    >
                        □{label}
                    </div>
                );
            })}
        </div>
    );
}

export default ItemGender;
