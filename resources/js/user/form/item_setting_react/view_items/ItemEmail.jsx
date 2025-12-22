/**
 */
function ItemEmail({item}) {
    const details = item.details ? JSON.parse(item.details) : {};
    const confirmType = details.confirm_type;

    return (
        <div className="flex flex-col gap-2">
            {confirmType === "1" && (
                <>
                    <div className="input-box w-full"><span className="text-gray-400" >メールアドレス</span></div>
                    <div className="input-box w-full"><span className="text-gray-400" >メールアドレス（確認用）</span></div>
                </>
            )}

            {confirmType === "2" && (
                <div className="input-box w-full"><span className="text-gray-400" >メールアドレス</span></div>
            )}
        </div>
    );
}

export default ItemEmail;
