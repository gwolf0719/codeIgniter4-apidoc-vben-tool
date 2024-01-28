# codeIgniter4-apidoc-vben-tool
這是一套可以利用 codeIgniter4 直接在指令行中建構後端相關功能的工具包
搭配的環境：
- codeIgniter4
- vben-admin
- apidoc

## 依照資料表建構 Model 檔案
> 我預設所有和資料表相關的 Model 檔案都是 Mod開頭的駝峰命名檔案（資料表名稱需要為小寫底線的蛇式命名）
```
php spark make:basemodel ModelName tableName
```
這樣會建構出對應的 Model 檔案，主要會將所有的資料表欄位填入這樣後面呼叫會比較輕鬆

## 建構 API 檔案和 apidoc 內容
> 有兩個作法可以使用
```
php spark make:apicontroller ControllerName ModelName TableName
```
> 這樣可以自己指定套用的 Model 和 table
> 如果你的用法 Controller 的名稱剛好和我一樣是資料表名稱改成駝峰，然後 Model 的名稱是 Controller 前面加 Mod 的話那你可以更快樂的寫成
```
php spark make:apicontroller ControllerName
```
> 後面兩個參數我會幫你處理掉
> 執行完後記得多執行，假設你已經在你的環境安裝 apidoc 了
```
apidoc -i app/Controllers -o public/doc
```

## 建構 vben-admin 的 crud 範本檔案
> 這個部分我會根據剛剛產出的 api 進行製作 vben-admin 需要的基本路由，API，vue
```
php spark make:vben tablename   
```
> 會在根目錄產生需要的 src 請將這些檔案複製到 vben-admin 的資料夾中即可

