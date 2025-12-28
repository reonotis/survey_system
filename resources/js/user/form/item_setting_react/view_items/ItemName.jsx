/**
 */
function ItemName({ item }) {
    console.log(item)
    const details = item.details;
    const nameSeparateType = details.name_separate_type;

    return (
        <div className="flex gap-2">
            {nameSeparateType === 1 && (
                <>
                    <div className="input-box w-full"><span className="text-gray-400" >田中</span></div>
                    <div className="input-box w-full"><span className="text-gray-400" >太郎</span></div>
                </>
            )}

            {nameSeparateType === 2 && (
                <div className="input-box w-full"><span className="text-gray-400" >田中 太郎</span></div>
            )}
        </div>
    );
}

export default ItemName;
