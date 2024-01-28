import { defHttp } from '@/utils/http/axios';

// 通用的 HTTP 請求函數
async function sendRequest(method, url, params = null) {
  try {
    const response = await defHttp[method](
      {
        url,
        params,
      },
      {
        errorMessageMode: 'modal',
      },
    );

    return response;
  } catch (error) {
    console.error('Error during HTTP request:', error);
    throw error;
  }
}

// 新增
export function PlaceHoderNameCreate(params) {
  return sendRequest('post', '/PlaceHoderName/create', params);
}

// 修改
export function PlaceHoderNameUpdate(params) {
  return sendRequest('post', '/PlaceHoderName/update', params);
}

// 利用 token 取得資料
export function PlaceHoderNameGetByToken() {
  return sendRequest('get', '/PlaceHoderName/get_by_token');
}

// 刪除
export function PlaceHoderNameDelete(params) {
  return sendRequest('post', '/PlaceHoderName/delete', params);
}

// 查詢指定單筆
export function PlaceHoderNameGet(sa_manager_id) {
  return sendRequest('get', `/PlaceHoderName/get/${sa_manager_id}`);
}

// 通用查詢列表
export function PlaceHoderNameList(params) {
  return sendRequest('post', '/PlaceHoderName/get_list', params);
}
