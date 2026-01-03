/**
 */
function ItemTerms({ item }) {

    return (
        <>
            <div className="
                    text-xs
                    whitespace-pre-line
                    border
                    border-gray-300
                    rounded
                    p-1
                    max-h-32
                    overflow-y-auto">
                {item.long_text}
            </div>

            <div className="">
                □ {item.details.label_name || '同意する'}
            </div>
        </>
    );
}

export default ItemTerms;
