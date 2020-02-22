<script src="hz_include/tinymce/tinymce.min.js"></script>
<?php
if ($_SESSION["username"] != null):
    if ($_POST["do"] != 8):?>
        <p>
            <script>
                tinymce.init({
                    language: 'zh_TW',
                    selector: 'textarea',
                    height: '600',
                    statusbar: false,
                    plugins: [
                        "advlist autolink lists link image charmap print preview hr anchor codesample",
                        "searchreplace wordcount visualblocks visualchars code fullscreen",
                        "insertdatetime media nonbreaking save table contextmenu directionality",
                        "emoticons template paste textcolor colorpicker textpattern imagetools"
                    ],
                    toolbar1: "insertfile undo redo | formatselect fontsizeselect | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | table pagebreak blockquote codesample",
                    toolbar2: "bold italic underline strikethrough subscript superscript | forecolor backcolor | link unlink image media",
                    menubar: false,
                    image_advtab: true,
                    relative_urls: false,
                    convert_urls: false,
                });
            </script>
            <label for="introductionSelf"></label>
            <textarea name="introductionSelf" id="introductionSelf" cols="100"
                      rows="20"><?php print $predata[8] ?></textarea>
        </p>
        <input type="text" name="do" hidden="hidden" value="8">
        <p style="text-align: center"><input style="width: 120px; height: 60px" type="submit" name="button"
                                             class="btn btn-primary" value="確認"/></p>
    <?php
    elseif ($_POST["do"] === "8"):
        $introductionSelf = addslashes($_POST["introductionSelf"]);
        $sqlintroductionSelf = "update admin set IntroductionSelf='$introductionSelf' where username='$b'";
        if (mysqli_query($sqli,$sqlintroductionSelf)):
            printmsg("suc", "您的關於我已經儲存"); ?>
            <meta http-equiv=REFRESH CONTENT=3;url=setting?action=introduction>
        <?php
        else:
            printmsg("error", "儲存過程發生錯誤，請重新嘗試"); ?>
        <?php endif; ?>
    <?php endif; ?>
<?php endif; ?>