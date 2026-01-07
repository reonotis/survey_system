import CommonAnnotationText from "./component/CommonAnnotationText.jsx";
import CommonChoices from "./component/CommonChoices.jsx";
import CommonRequire from "./component/CommonRequire.jsx";
import CommonTitle from "./component/CommonTitle.jsx";

/**
 * セレクトボックス設定
 */
function ItemTypeSelectBox({selectedItem, updateItemLocalValue, saveItemValue}) {



    return (
        <div className="space-y-4">
            <CommonTitle
                selectedItem={selectedItem}
                updateItemLocalValue={updateItemLocalValue}
                saveItemValue={saveItemValue}
            />
            <CommonRequire
                selectedItem={selectedItem}
                updateItemLocalValue={updateItemLocalValue}
                saveItemValue={saveItemValue}
            />
            <CommonAnnotationText
                selectedItem={selectedItem}
                updateItemLocalValue={updateItemLocalValue}
                saveItemValue={saveItemValue}
            />
            <CommonChoices
                selectedItem={selectedItem}
                updateItemLocalValue={updateItemLocalValue}
                saveItemValue={saveItemValue}
            />

        </div>
    );
}

export default ItemTypeSelectBox;

