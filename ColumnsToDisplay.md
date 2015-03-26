**Columns to Display**

Currently the following columns are available for display & by default all are displayed. You can cusotmize the display columns, do the follwing:
  1. To remove a column:
    1. Unset $wgPageAttachment\_colToDisplay[.md](.md).  Examle:
```
     unset($wgPageAttachment_colToDisplay);
```
    1. Set the columns to be displayed
    1. Set the column widths $wgPageAttachment\_colWidth[.md](.md) so that the sum of the column withds add up to 100
    1. Ensure that title row's column span total match to the total number of columns going to be displayed
  1. To change the order of the columns: <br /> The columns are displayed in the sequence added to $wgPageAttachment\_colToDisplay. So, to change the display order, specify the column names in the sequence you would like them to be displayed.
```
$wgPageAttachment_colToDisplay[] = 'Name';
$wgPageAttachment_colToDisplay[] = 'Description';
$wgPageAttachment_colToDisplay[] = 'Size';
$wgPageAttachment_colToDisplay[] = 'DateUploaded';
$wgPageAttachment_colToDisplay[] = 'UploadedBy';
$wgPageAttachment_colToDisplay[] = 'Buttons';
```