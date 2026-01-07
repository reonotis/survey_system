
/**
 * 必須項目の設定
 */
function CommonRequire({selectedItem, updateItemLocalValue, saveItemValue}) {
    const updateFieldRequired = (value) => {
        const fieldRequiredValue =  value ? 1 : 0
        updateItemLocalValue(selectedItem.id, "field_required", fieldRequiredValue);
        saveItemValue(selectedItem.id, 'field_required', fieldRequiredValue)
    }

    return (
        <div className="border-b pb-3">
            <label className="flex items-center w-fit cursor-pointer">
                <div className="checkbox-content">
                    <input
                        type="checkbox"
                        name="field_required"
                        className="checkbox"
                        value="1"
                        checked={selectedItem.field_required}
                        onChange={e => updateFieldRequired(e.target.checked)}
                    />
                </div>
                <span className="text-sm text-gray-700 p-1">必須項目</span>
            </label>
        </div>
    );
}

export default CommonRequire;
