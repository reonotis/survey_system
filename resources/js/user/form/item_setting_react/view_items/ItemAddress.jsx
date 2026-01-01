import React from 'react';

function ItemAddress({ item }) {
    // details を安全にパース
    const details = item.details;
    const usePostCodeType = details.use_post_code_type;
    const addressSeparateType = details.address_separate_type;

    return (
        <div className="flex gap-2 flex-col" >
            {usePostCodeType === 1 && (
                <div className="flex-start-center gap-2">
                    <div className="input-box w-20"><span className="text-gray-400" >100</span></div>
                    <div >-</div>
                    <div className="input-box w-20"><span className="text-gray-400" >0001</span></div>
                </div>
            )}

            {addressSeparateType && (
                addressSeparateType === 1 ? (
                    <div className="flex gap-2 flex-col" >
                        <div className="flex-start-center gap-2">
                            <div className="input-box w-36"><span className="text-gray-400" >東京都</span></div>
                            <div className="input-box w-48"><span className="text-gray-400" >千代田区</span></div>
                        </div>
                        <div className="input-box w-full"><span className="text-gray-400" >1-1-1〇〇〇マンション101</span></div>
                    </div>
                ) : (
                    <div className="flex-start-center gap-2">
                        <div className="input-box w-full"><span className="text-gray-400" >東京都千代田区1-1-1〇〇〇マンション101</span></div>
                    </div>
                )
            )}
        </div>
    );
}

export default ItemAddress;
