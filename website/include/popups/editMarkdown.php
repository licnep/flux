<div class="modal-header">
    <h2>Edit Markdown:</h2>
</div>
<div class="modal-body">
    <a href="http://support.mashery.com/docs/customizing_your_portal/Markdown_Cheat_Sheet" target="about:blank">Markdown syntax help</a>
    <textarea id="markdown" class="xlarge" rows="7" style="width:100%"></textarea>
    <div class="well" style="margin-top:20px">
        <div class="wellTitleBar"><div class="wellTitle">Preview:</div></div>
        <div id="preview"></div>
    </div>
</div>
<div class="modal-footer">
    <button class="btn success" id="saveBtn">Save changes</button>
</div>
<script type="text/javascript" src="include/markdown/showdown.js"></script>
<script type="text/javascript">
    $('#markdown').val($FW.popups.editMarkdown.data['text']);
    if ($FW.popups.editMarkdown.data['converter']==undefined) {
        $FW.popups.editMarkdown.data['converter'] = new Showdown.converter();
    }
    
    $FW.popups.editMarkdown.data.updatePreview = function () {
        $('#preview').html( $FW.popups.editMarkdown.data['converter'].makeHtml($('#markdown').val()) );
    };
    
    $FW.popups.editMarkdown.data.updatePreview();
    $('#markdown').keyup($FW.popups.editMarkdown.data.updatePreview);
    $('#saveBtn').click(function() {
        //TODO this whole 'hide and then return' thing should be abstracted and made used by all modal dialogs
        modal = $('#markdown').parents('.modal');
        var text = $('#markdown').val();
        modal.bind('hidden',function() {
            $FW.popups.editMarkdown.callback( {'text':text} );
        });
        modal.modal('hide');
    });
</script>
