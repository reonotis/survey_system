/**
 */
function ItemName({item}) {
    const kanaSeparateType = item.details.kana_separate_type;

    return (
        <div className="flex gap-2">
            {kanaSeparateType === 1 && (
                <>
                    <div className="input-box w-full"><span className="text-gray-400" >タナカ</span></div>
                    <div className="input-box w-full"><span className="text-gray-400" >タロウ</span></div>
                </>
            )}

            {kanaSeparateType === 2 && (
                <div className="input-box w-full"><span className="text-gray-400" >タナカ タロウ</span></div>
            )}
        </div>
    );
}

export default ItemName;
