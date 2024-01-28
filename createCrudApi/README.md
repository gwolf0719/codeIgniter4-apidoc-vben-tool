## createCrudApi 建構 API 檔案和 apidoc 內容
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

## install
> 將 createCrudApi 複製到專案的 app 目錄下