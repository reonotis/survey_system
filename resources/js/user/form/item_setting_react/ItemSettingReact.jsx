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
            <div className="contents-area p-2" style={{ width: '200px' }}>
                <SelectableItemsList
                    addableItems={addableItems}
                    onAddItem={handleAddItem}
                />
            </div>

            <div className="contents-area p-2" style={{ flex: '1', maxWidth: '600px',  maxHeight: 'calc(100vh - 200px)' }}>
                <CurrentItemsList
                    draftFormItems={draftFormItems}
                    onSortEnd={handleOrderChange}
                    setEditingItemId={setEditingItemId}
                    itemDelete={itemDelete}
                    selectedItem={selectedItem}
                />
            </div>

            <div className="contents-area p-2" style={{ flex: '1', width: '600px', maxHeight: 'calc(100vh - 200px)' }}>
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
