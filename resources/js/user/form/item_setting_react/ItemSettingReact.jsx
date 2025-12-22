import { createRoot } from 'react-dom/client';
import SelectableItemsList from './SelectableItemsList';
import CurrentItemsList from './CurrentItemsList';
import ItemDetailPanel from './ItemDetailPanel';
import { useDraftFormItems } from './useDraftFormItems';

function ItemSettingReact() {
    const {
        draftFormItems,
        addableItems,
        handleAddItem,
        handleOrderChange,
        setEditingItemId,
        selectedItem,
        updateItemLocalValue,
        saveItemValue,
        itemDelete,
    } = useDraftFormItems(window.draftFormItems ?? []);

    return (
        <div className="flex gap-2 mt-4">
            <div className="bg-white rounded-lg shadow px-2" style={{ width: '200px' }}>
                <SelectableItemsList
                    addableItems={addableItems}
                    onAddItem={handleAddItem}
                />
            </div>

            <div className="bg-white rounded-lg shadow px-2" style={{ flex: '1' }}>
                <CurrentItemsList
                    draftFormItems={draftFormItems}
                    onSortEnd={handleOrderChange}
                    setEditingItemId={setEditingItemId}
                    itemDelete={itemDelete}
                />
            </div>

            <div className="bg-white rounded-lg shadow px-2" style={{ width: '600px' }}>
                <ItemDetailPanel
                    selectedItem={selectedItem}
                    updateItemLocalValue={updateItemLocalValue}
                    saveItemValue={saveItemValue}
                />
            </div>
        </div>
    );
}

const container = document.getElementById('react-item-setting-container');
if (container) {
    createRoot(container).render(<ItemSettingReact />);
}
