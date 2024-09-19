// Menyiapkan Quill editor
var quill = new Quill('#snow-editor', {
    theme: 'snow'
});

// Fungsi untuk menangkap konten editor dan menyimpannya ke dalam input tersembunyi
function saveEditorContent() {
    var editorContent = document.querySelector('.ql-editor').innerHTML;
    document.getElementById('snow-editor-content').value = editorContent;
}

// Memanggil fungsi saveEditorContent saat formulir diserahkan
$('form').submit(function() {
    saveEditorContent();
});