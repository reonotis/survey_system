/**
 */
function ItemTel({item}) {
    const details = item.details ? JSON.parse(item.details) : {};
    const hyphenType = details.hyphen_type;

    return (
        <div className="flex flex-col gap-2">
            {hyphenType === "1" && (
                <>
                    <div className="input-box w-full"><span className="text-gray-400" >090-1234-5678</span></div>
                </>
            )}

            {hyphenType === "2" && (
                <div className="input-box w-full"><span className="text-gray-400" >09012345678</span></div>
            )}
        </div>
    );
}

export default ItemTel;
