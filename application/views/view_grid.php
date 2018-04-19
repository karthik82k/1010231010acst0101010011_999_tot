<!DOCTYPE html>
<html>
<head>
    <title></title>
    <script type="text/javascript" src="https://test.newui.myddf.info/js/jquery.min.js"></script>
 
</head>
<body>
<div>
 <table width="95%" cellpadding="0" cellspacing="0" border="1" id="textdata">
                <tr>
                    <td width="20%"  align="center" valign="middle" style="font-weight: bold;">Item Name</td>
                    <td width="40%" align="center" valign="middle" style="font-weight: bold;">Qty</td>
                    <td  align="center" valign="middle" style="font-weight: bold;">Unity Price</td>
                    <td  align="center" valign="middle" style="font-weight: bold;">Discount Price</td>
                    <td  width="4%"></td>
                </tr>
                
            <tr class="to_clone1 ">
                        <td align="left" width="20%" valign="middle" style="padding: 10px;">
                            <select id="item_name" name="item_name[]" class="tf4">
                              <option>select</option>  
                            </select>

                         <!--   <textarea name="competency_required[]" id="competency_required" class="tf4 inputtextbox form-control animated"></textarea> -->
                        </td>
                        <td align="left" width="40%" valign="middle" style="padding: 10px;">
                            <textarea name="expected_level[]" id="expected_level" class="tf5 inputtextbox form-control animated"></textarea>
                        </td>
                        <td align="center" width="30%" valign="top" colspan="2">
                            <table width="100%" cellpadding="0" cellspacing="0" border="1" style="border: thin #000000;">
                                <tr>
                                    <td width="50%" align="center" valign="middle" height="60" style="padding: 10px;">
                                        <textarea name="actual_reviewee[]" id="actual_reviewee" class="tf6 inputtextbox form-control animated"></textarea>
                                    </td>
                                    <td width="50%" align="center" valign="middle" height="60" style="padding: 10px;">
                                        <textarea name="actual_reviewer[]" id="actual_reviewer" class="tf7 inputtextareabox form-control animated" disabled="disabled"></textarea>
                                    </td>                           
                                </tr>
                            </table>
                        </td>
                        <td width="4%" align="center"><img src="https://test.newui.myddf.info/images/add.png" name="multiple" id="multiple" class="f_moreFilter" alt="Add Multiple" title="Add Multiple" /></span> 
                            <span class="f_deleteimg"></span>
                        </td>
                    </tr>
                                </table>
    </div>

</body>
<script type="text/javascript">
     var id = 1;
    $(".f_moreFilter").click(function() {
        var new_div1 = $(this).closest('table').find('.to_clone1').eq(0).clone(true).appendTo("#textdata");

        var tf4 = new_div1.find('.tf4').eq(0);
        var tf5 = new_div1.find('.tf5').eq(0);
        var tf6 = new_div1.find('.tf6').eq(0);
        var tf7 = new_div1.find('.tf7').eq(0);

        tf4.val('').css('height', 'auto');
        tf5.val('').css('height', 'auto');
        tf6.val('').css('height', 'auto');
        tf7.val('').css('height', 'auto');

        fdp8_ = tf4.attr('id');
        tf4.attr('id', fdp8_ + id);

        fdp9_ = tf5.attr('id');
        tf5.attr('id', fdp9_ + id);

        fdp10_ = tf6.attr('id');
        tf6.attr('id', fdp10_ + id);

        fdp11_ = tf7.attr('id');
        tf7.attr('id', fdp11_ + id);

        var img = $('<img></img>', {
            src: "https://test.newui.myddf.info/images/close.png",
            class: "f_deleteFilter1",
            width: 18,
            height: 18,
            style: "cursor:  pointer;",
            alt: "Delete",
            title: "Delete"
        });
        new_div1.find('span.f_deleteimg').append(img);

        id++;
    });

    $(".f_deleteFilter1").live('click', function() {
        $(this).closest('tr').remove();
        id--;
    });

    $(".tf5").live('click', function() {

        alert($('.tf5').attr('id'));
    });

    </script>
</html>