// 項目設定のデータを保持するモジュール
// 他のJSXファイルから直接参照可能

let formItemsData = [];
let selectedItemData = null;
let itemTypeListData = {};
let commonConstsData = {};
let registerFormItemUrlData = '';
let updateItemOrderUrlData = '';
let csrfTokenData = '';
let saveItemFunction = null; // 保存処理の関数を保持

// データを設定する関数
export function updateFormItems(items) {
    formItemsData = items;
}

export function updateSelectedItem(item) {
    selectedItemData = item;
}

export function setItemTypeList(list) {
    itemTypeListData = list;
}

export function setCommonConsts(consts) {
    commonConstsData = consts;
}

export function setRegisterFormItemUrl(url) {
    registerFormItemUrlData = url;
}

export function setUpdateItemOrderUrl(url) {
    updateItemOrderUrlData = url;
}

export function setCsrfToken(token) {
    csrfTokenData = token;
}

// 保存処理の関数を登録
export function setSaveItemFunction(fn) {
    saveItemFunction = fn;
}

// 初期化関数（まとめて設定）
export function initItemSettingData(data) {
    if (data.formItems !== undefined) formItemsData = data.formItems;
    if (data.selectedItem !== undefined) selectedItemData = data.selectedItem;
    if (data.itemTypeList !== undefined) itemTypeListData = data.itemTypeList;
    if (data.commonConsts !== undefined) commonConstsData = data.commonConsts;
    if (data.registerFormItemUrl !== undefined) registerFormItemUrlData = data.registerFormItemUrl;
    if (data.updateItemOrderUrl !== undefined) updateItemOrderUrlData = data.updateItemOrderUrl;
    if (data.csrfToken !== undefined) csrfTokenData = data.csrfToken;
}

// データを取得する関数（直接参照も可能だが、関数経由を推奨）
export function getFormItems() {
    return formItemsData;
}

export function getSelectedItem() {
    return selectedItemData;
}

export function getItemTypeList() {
    return itemTypeListData;
}

export function getCommonConsts() {
    return commonConstsData;
}

export function getRegisterFormItemUrl() {
    return registerFormItemUrlData;
}

export function getUpdateItemOrderUrl() {
    return updateItemOrderUrlData;
}

export function getCsrfToken() {
    return csrfTokenData;
}

// 全てのデータを一度に取得
export function getAllData() {
    return {
        formItems: formItemsData,
        selectedItem: selectedItemData,
        itemTypeList: itemTypeListData,
        commonConsts: commonConstsData,
        registerFormItemUrl: registerFormItemUrlData,
        updateItemOrderUrl: updateItemOrderUrlData,
        csrfToken: csrfTokenData,
    };
}

// 保存処理を実行（itemId と payload を渡す）
export function saveItem(itemId, payload) {
    console.log(payload);
    // データストア内の該当項目を更新
    const itemIndex = formItemsData.findIndex((item) => item.id === itemId);
    if (itemIndex !== -1) {
        // payloadのキーバリューで項目を直接上書き
        Object.keys(payload).forEach(key => {
            formItemsData[itemIndex][key] = payload[key];
        });

        // 選択中の項目も更新されている場合は、それも更新
        if (selectedItemData && selectedItemData.id === itemId) {
            selectedItemData = formItemsData[itemIndex];
        }
    }

    // サーバーに保存
    if (saveItemFunction) {
        saveItemFunction(itemId, payload);
    } else {
        console.warn('保存処理が登録されていません');
    }
}

// 特定のIDの項目を取得
export function getItemById(itemId) {
    return formItemsData.find((item) => item.id === itemId);
}

// デバッグ用：window オブジェクトにも公開（コンソールから確認可能）
if (typeof window !== 'undefined') {
    window.itemSettingData = {
        getFormItems,
        getSelectedItem,
        getItemTypeList,
        getCommonConsts,
        getRegisterFormItemUrl,
        getUpdateItemOrderUrl,
        getCsrfToken,
        getAllData,
        saveItem,
        getItemById,
        // 直接参照も可能（非推奨だが、デバッグ用）
        formItems: () => formItemsData,
        selectedItem: () => selectedItemData,
        itemTypeList: () => itemTypeListData,
        commonConsts: () => commonConstsData,
    };
}
