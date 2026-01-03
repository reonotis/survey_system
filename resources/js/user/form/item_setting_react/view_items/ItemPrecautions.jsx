/**
 */
function ItemPrecautions({ item }) {

    return (
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
    );
}

export default ItemPrecautions;
