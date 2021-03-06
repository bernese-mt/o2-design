
/**
 * 後台JS文件的多語言包
 */
function eyou_lang(key, str) {
    var langArr = new Array();
    langArr['Close shortcut management']='關閉快捷管理';
    langArr['Display shortcut management']='顯示快捷管理';
    langArr['No upgrades may have security risks']='不升級可能有安全隐患';
    langArr['Get Pinyin']='獲取拼音';
    langArr['List template']='列表模板';
    langArr['Template file does not exist']='模板文件不存在';
    langArr['Single page template']='單頁模板';
    langArr['Message template']='留言模板';
    langArr['Fill in column names first']='先填寫欄目名稱';
    langArr['Loading……']='加載中……';
    langArr['Column names should not be empty']='欄目名稱不能爲空';
    langArr['The file save directory cannot be empty']='文件保存目錄不能爲空';
    langArr['Column English name can not be pure number']='欄目英文名不能爲純數字';
    langArr['Please select the list template']='請選擇列表模板';
    langArr['Please select document template']='請選擇文檔模板';
    langArr['The file save directory already exists']='文件保存目錄已存在';
    langArr['Unfold all sub columns']='展開所有子欄目';
    langArr['Close all sub columns']='關閉所有子欄目';
    langArr['If there are sub columns and documents will be emptied together to confirm deletion?']='<font color="#ff0000">如有子欄目及文檔将一起清空</font>，确認删除？';
    langArr['Please select columns…']='請選擇欄目…';
    langArr['The format of the field name is incorrect']='字段名稱格式不正确';
    langArr['Please upload pictures']='請選擇上傳圖片';
    langArr['Please select upload files']='請選擇上傳文件';
    langArr['Please upload the SQL file']='請上傳sql文件';
    langArr['The name of the advertisement should not be empty']='廣告名稱不能爲空';
    langArr['Please select a location']='請選擇位置';
    langArr['The name of the advertisement must not be empty']='廣告位名稱不能爲空';
    langArr['Please copy and paste the corresponding label code into the corresponding template file']='請将對應标簽代碼複制并粘貼到對應模板文件中！';
    langArr['Please select your role']='請選擇所屬角色';
    langArr['User name can not be empty']='用戶名不能爲空';
    langArr['The password must not be empty']='密碼不能爲空';
    langArr['Please input the original password']='請輸入原密碼';
    langArr['The length of the original password can not be less than 5 bits']='原始密碼長度不能少于5位';
    langArr['Please enter a new password']='請輸入新密碼';
    langArr['The length of the new password can not be less than 5 bits']='新密碼長度不能少于5位';
    langArr['Please enter confirmation password']='請輸入确認密碼';
    langArr['Confirm password length can not be less than 5 bits']='确認密碼長度不能少于5位';
    langArr['Two password inconsistency']='兩次密碼輸入不一致';
    langArr['The verification code can not be empty']='驗證碼不能爲空';
    langArr['Ready to enter']='准備進入';
    langArr['Please contact webmaster']='請聯系網站管理員';
    langArr['This column does not allow publishing documents']='該欄目不允許發布文檔';
    langArr['The title should not be empty']='标題不能爲空';
    langArr['Move document']='移動文檔';
    langArr['Target columns do not belong to the same model']='目标欄目不屬于同一模型';
    langArr['The name of the model cannot be empty']='模型名稱不能爲空';
    langArr['Model identifier can not be empty']='模型标識不能爲空';
    langArr['The name of the data table cannot be empty']='數據表名不能爲空';
    langArr['The controller name can not be empty']='控制器名不能爲空';
    langArr['The name of the left menu can not be empty']='左側菜單名稱不能爲空';
    langArr['Field name']='字段名稱';
    langArr['Field names can not be digits']='字段名稱不能純數字';
    langArr['Field header cannot be empty']='字段标題不能爲空';
    langArr['Please select field type']='請選擇字段類型';
    langArr['The default value can not be empty']='默認值不能爲空';
    langArr['Data will not be recovered, confirm deletion?']='<font color="#ff0000">數據将無法恢複</font>，确認删除？';
    langArr['The working directory must not be empty']='工作目錄不能爲空';
    langArr['The name of the file can not be empty']='文件名稱不能爲空';
    langArr['New location can not be empty']='新位置不能爲空';
    langArr['New directory should not be empty']='新目錄不能爲空';
    langArr['New name should not be empty']='新名稱不能爲空';
    langArr['The name of the form can not be empty']='表單名稱不能爲空';
    langArr['Please select the form type']='請選擇表單類型';
    langArr['The list of optional values cannot be empty']='可選值列表不能爲空';
    langArr['URL URL can not be empty']='網址URL不能爲空';
    langArr['The name of the website can not be empty']='網站名稱不能爲空';
    langArr['Please upload website Logo pictures']='請上傳網站Logo圖片';
    langArr['Failed to load, click here']='加載失敗，點擊此處';
    langArr['Refresh']='刷新';
    langArr['The parameter name cannot be empty']='參數名稱不能爲空';
    langArr['Content page']='内容頁';
    langArr['List page']='列表頁';
    langArr['Custom variables']='自定義變量';
    langArr['Variable names cannot be empty']='變量名稱不能爲空';
    langArr['AccessKeyId can not be empty']='AccessKeyId 不能爲空';
    langArr['AccessKeySecret can not be empty']='AccessKeySecret 不能爲空';
    langArr['Endpoint can not be empty']='Endpoint 不能爲空';
    langArr['Bucket can not be empty']='Bucket 不能爲空';
    langArr['Please fill in the correct test cell phone number']='請填寫正确的測試手機号碼';
    langArr['Please fill in the SMS platform [appkey]']='請填寫短信平台[appkey]';
    langArr['Please fill in the SMS platform [secretKey]']='請填寫短信平台[secretKey]';
    langArr['Please fill in the company name / brand name / product name']='請填寫公司名/品牌名/産品名';
    langArr['Please fill in the email sending server address']='請填寫郵件發送服務器地址';
    langArr['Please fill in the correct email account']='請填寫正确的郵箱賬号';
    langArr['Please fill in the password for sending the mailbox']='請填寫發送郵箱密碼';
    langArr['Please fill in the correct test email account']='請填寫正确的測試郵箱賬号';
    langArr['Label call']='标簽調用';
    langArr['Please select the data table to be backed up']='請選中要備份的數據表';
    langArr['Sending backup requests...']='正在發送備份請求...';
    langArr['Initialization is successful. Please do not close this page']='初始化成功，請不要關閉本頁面！';
    langArr['Start backup, please do not close this page!']='開始備份，請不要關閉本頁面！';
    langArr['Backup database, please do not close!']='正在備份數據庫，請不要關閉！';
    langArr['Immediate backup']='立即備份';
    langArr['Start backing up']='開始備份';
    langArr['Backup table']='正在備份表';
    langArr['Initialization success! Start backing up... Please do not close this page!']='初始化成功！開始備份……，請不要關閉本頁面！';
    langArr['Initialization success! Backup table']='初始化成功！正在備份表';
    langArr[', Please do not close this page!']='，請不要關閉本頁面！';
    langArr['Backup completed... 100%, please do not close this page!']='備份完成……100%，請不要關閉本頁面！';
    langArr['Backup completed... 100%, click back to backup']='備份完成……100%，點擊重新備份';
    langArr['Backup success']='備份成功';
    langArr['Detection plug-in update']='檢測插件更新';
    langArr['Updated version']='已升級最新版本';
    langArr['Please upload zip compressed package']='請上傳zip壓縮包';
    langArr['Confirm upload decompression?']='确認上傳解壓？';
    langArr['Please proceed with caution. Do you want to uninstall the plugin?']='請謹慎操作，是否卸載該插件？';
    langArr['Retain data only']='僅保留數據';
    langArr['Batch mobile confirmation']='确認批量移動';
    langArr['0 pictures allowed']='允許上傳0張圖片';
    langArr['More than N words']='已超出<em class="error">'+str+'</em>個字';
    langArr['You can enter N words']='還可以輸入<em>'+str+'</em>個字';
    langArr['Tip: system update will not involve foreground template and website data']='<font color="red">小提示：系統更新不會涉及前台模板及網站數據等。</font>';
    langArr['Latest version of detection system:']='檢測系統最新版：';
    langArr['This reminder can be opened in core settings']='【核心設置】裏可以開啓該提醒';
    langArr['No longer reminding']='不再提醒';
    langArr['Ignore']='忽略';
    langArr['Upgrade']='升級';
    langArr['Got it']='知道了';
    langArr['Upgradeing']='升級中';
    langArr['No upgrades may have security risks']='不升級可能有安全隐患';
    langArr['Being dealt with']='正在處理';
    langArr['This operation is irreversible. Confirm the complete unloading?']='此操作不可逆，确認徹底卸載？';
    langArr['Complete unload']='徹底卸載';
    langArr['Network request failed']='網絡失敗，請刷新頁面后重試';
    langArr['Successful operation']='操作成功';
    langArr['Operation failed']='操作失敗';
    langArr['There is no list of documents in this column']='該欄目沒有文檔列表';
    langArr['Batch deleting']='批量删除';
    langArr['This operation is irreversible. Confirm deletion?']='此操作不可逆，确認删除？';
    langArr['Code call']='代碼調用';
    langArr['Do not refresh the page']=str+'...&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;請勿刷新頁面';
    langArr['Please choose the column of the corresponding model']='請選擇對應模型的欄目';
    langArr['Please choose at least one item']='請至少選擇一項';
    langArr['Default value']='默認值';
    langArr['Send in']='發送中';
    langArr['Send success']='發送成功';
    langArr['Replicating success']='複制成功';
    langArr['Replication failed! Please copy manually']='複制失敗！請手動複制';
    langArr['Please be patient']='請耐心等待';
    langArr['Click to select pictures']='點擊選擇圖片';
    langArr['Tips']='提示';
    langArr['Update success']='更新成功';
    langArr['Confirm']='确定';
    langArr['Cancel']='取消';
    langArr['This operation is irreversible. Confirm batch deletion?']='此操作不可逆，确認批量删除？';
    langArr['Upload a picture']='上傳圖片';
    langArr['Error of parameters']='參數有誤';
    langArr['Yes']='是';
    langArr['Confirmation of submission']='確認送出';
    langArr['Lack of system parameters: ID, type, page, try to request technical support']='缺少系統參數：id、type、page，嘗試請求技術支持';
    langArr['The following box list']='下列框列表';

    return langArr[key];
}
