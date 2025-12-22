import React from 'react';

function ItemGender({ item }) {
    // details を安全にパース
    const details = item.details ? JSON.parse(item.details) : {};

    // 選択されている gender の配列（例: ["2", "1"]）
    const genderList = details.gender_list || [];

    const genderConst = window.commonConst.GENDER_LIST || {};

    return (
        <div className="flex gap-4">
            {genderList.length === 0 && (
                <div className="text-sm text-gray-400">
                    未選択
                </div>
            )}

            {Object.entries(genderConst).map(([value, label]) => {
                if (!genderList.includes(value)) return null;

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
