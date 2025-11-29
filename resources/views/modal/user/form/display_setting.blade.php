<div class="modal-background" id="display_setting_modal" style="display: none;">
    <div class="modal-contents-area" style="width: 500px;">
        <div class="modal-close">×</div>

        <div class="modal-content">

            <form method="POST" action="{{ route('user_form_update_display_items', ['form_setting' => $form_setting->id]) }}">
                @csrf

                <table class="list-tbl" style="width: 100%;">
                    <thead>
                    <tr>
                        <th style="flex: 1;">表示する項目</th>
                    </tr>
                    </thead>

                    <tbody id="form-items-sortable">
                    @foreach($form_setting->formItems as $form_item)
                        <tr>
                            <td class="p-4">
                                @php
                                    $item_title = App\Models\FormItem::ITEM_TYPE_LIST[$form_item->item_type];
                                    if($form_item->item_title) {
                                        $item_title = $form_item->item_title;
                                    }
                                @endphp
                                <x-input-checkbox
                                    name="display_items[]"
                                    label="{{ $item_title }}"
                                    id="{{ $item_title }}"
                                    value="{{ $form_item->id }}"
                                    :checked="in_array(  $form_item->id, $display_item_ids)"
                                />
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

                <div class="item-row flex-center-center">
                    <input type="submit" class="btn" value="更新"/>
                </div>
            </form>

        </div>
    </div>
</div>
