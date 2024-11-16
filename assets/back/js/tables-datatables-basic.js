$(document).ready(function() {
    $("#example1").DataTable({
        paging: true,
        responsive: true,
        lengthChange: false,
        pageLength: 10, 
        autoWidth: false,
        buttons: [{
            extend: 'collection',
            className: 'btn btn-label-primary dropdown-toggle me-2 waves-effect waves-light',
            text: '<i class="mdi mdi-export-variant me-sm-1"></i> <span class="d-none d-sm-inline-block">Export</span>',
            buttons: [{
                    extend: 'print',
                    text: '<i class="mdi mdi-printer-outline me-1"></i>Print',
                    className: 'dropdown-item',
                    exportOptions: {
                        columns: ':not(:last-child)',
                        format: {
                            body: function(inner, coldex, rowdex) {
                                if (inner.length <= 0) return inner;
                                var el = $.parseHTML(inner);
                                var result = '';
                                $.each(el, function(index, item) {
                                    if (item.classList !== undefined && item.classList.contains('user-name')) {
                                        result = result + item.lastChild.firstChild.textContent;
                                    } else if (item.innerText === undefined) {
                                        result = result + item.textContent;
                                    } else result = result + item.innerText;
                                });
                                return result;
                            }
                        }
                    },
                    customize: function(win) {
                        $(win.document.body)
                            .css('color', '#000')
                            .css('border-color', '#ccc')
                            .css('background-color', '#fff');
                        $(win.document.body)
                            .find('table')
                            .addClass('compact')
                            .css('color', 'inherit')
                            .css('border-color', 'inherit')
                            .css('background-color', 'inherit');
                    }
                },

                {
                    extend: 'pdf',
                    text: '<i class="mdi mdi-file-pdf-box me-1"></i>Pdf',
                    className: 'dropdown-item',
                    exportOptions: {
                        columns: ':not(:last-child)',
                        format: {
                            body: function(inner, coldex, rowdex) {
                                if (inner.length <= 0) return inner;
                                var el = $.parseHTML(inner);
                                var result = '';
                                $.each(el, function(index, item) {
                                    if (item.classList !== undefined && item.classList.contains('user-name')) {
                                        result = result + item.lastChild.firstChild.textContent;
                                    } else if (item.innerText === undefined) {
                                        result = result + item.textContent;
                                    } else result = result + item.innerText;
                                });
                                return result;
                            }
                        }
                    },
                    customize: function(doc) {
                        // Customization
                        doc.content.splice(0, 1); // Remove default header
                        doc.styles.tableHeader = {
                            bold: true,
                            fontSize: 11,
                            color: 'black',
                            fillColor: '#f2f2f2',
                            alignment: 'center'
                        };
                        doc.styles.title = {
                            color: 'black',
                            fontSize: '12',
                            alignment: 'center'
                        };
                        doc.content[0].text = 'Data Export';
                    }
                },
                {
                    extend: 'excel',
                    text: '<i class="mdi mdi-file-excel-outline me-1"></i>Excel',
                    className: 'dropdown-item',
                    exportOptions: {
                        columns: ':not(:last-child)',
                        format: {
                            body: function(inner, coldex, rowdex) {
                                if (inner.length <= 0) return inner;
                                var el = $.parseHTML(inner);
                                var result = '';
                                $.each(el, function(index, item) {
                                    if (item.classList !== undefined && item.classList.contains('user-name')) {
                                        result = result + item.lastChild.firstChild.textContent;
                                    } else if (item.innerText === undefined) {
                                        result = result + item.textContent;
                                    } else result = result + item.innerText;
                                });
                                return result;
                            }
                        }
                    }
                },
                {
                    extend: 'copy',
                    text: '<i class="mdi mdi-content-copy me-1"></i>Copy',
                    className: 'dropdown-item',
                    exportOptions: {
                        columns: ':not(:last-child)',
                        format: {
                            body: function(inner, coldex, rowdex) {
                                if (inner.length <= 0) return inner;
                                var el = $.parseHTML(inner);
                                var result = '';
                                $.each(el, function(index, item) {
                                    if (item.classList !== undefined && item.classList.contains('user-name')) {
                                        result = result + item.lastChild.firstChild.textContent;
                                    } else if (item.innerText === undefined) {
                                        result = result + item.textContent;
                                    } else result = result + item.innerText;
                                });
                                return result;
                            }
                        }
                    }
                }
            ]
        }],
        dom: '<"card-header flex-column flex-md-row"<"head-label text-center"><"dt-action-buttons text-end"B>><"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
        displayLength: 20,
        lengthMenu: [20, 50, 75, 100]
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

    $("#example2").DataTable({
        responsive: true,
        lengthChange: false,
        autoWidth: false,
        buttons: [{
            extend: 'collection',
            className: 'btn btn-label-primary dropdown-toggle me-2 waves-effect waves-light',
            text: '<i class="mdi mdi-export-variant me-sm-1"></i> <span class="d-none d-sm-inline-block">Export</span>',
            buttons: [{
                    extend: 'print',
                    text: '<i class="mdi mdi-printer-outline me-1"></i>Print',
                    className: 'dropdown-item',
                    exportOptions: {
                        columns: ':not(:last-child)',
                        format: {
                            body: function(inner, coldex, rowdex) {
                                if (inner.length <= 0) return inner;
                                var el = $.parseHTML(inner);
                                var result = '';
                                $.each(el, function(index, item) {
                                    if (item.classList !== undefined && item.classList.contains('user-name')) {
                                        result = result + item.lastChild.firstChild.textContent;
                                    } else if (item.innerText === undefined) {
                                        result = result + item.textContent;
                                    } else result = result + item.innerText;
                                });
                                return result;
                            }
                        }
                    },
                    customize: function(win) {
                        $(win.document.body)
                            .css('color', '#000')
                            .css('border-color', '#ccc')
                            .css('background-color', '#fff');
                        $(win.document.body)
                            .find('table')
                            .addClass('compact')
                            .css('color', 'inherit')
                            .css('border-color', 'inherit')
                            .css('background-color', 'inherit');
                    }
                },

                {
                    extend: 'pdf',
                    text: '<i class="mdi mdi-file-pdf-box me-1"></i>Pdf',
                    className: 'dropdown-item',
                    exportOptions: {
                        columns: ':not(:last-child)',
                        format: {
                            body: function(inner, coldex, rowdex) {
                                if (inner.length <= 0) return inner;
                                var el = $.parseHTML(inner);
                                var result = '';
                                $.each(el, function(index, item) {
                                    if (item.classList !== undefined && item.classList.contains('user-name')) {
                                        result = result + item.lastChild.firstChild.textContent;
                                    } else if (item.innerText === undefined) {
                                        result = result + item.textContent;
                                    } else result = result + item.innerText;
                                });
                                return result;
                            }
                        }
                    },
                    customize: function(doc) {
                        // Customization
                        doc.content.splice(0, 1); // Remove default header
                        doc.styles.tableHeader = {
                            bold: true,
                            fontSize: 11,
                            color: 'black',
                            fillColor: '#f2f2f2',
                            alignment: 'center'
                        };
                        doc.styles.title = {
                            color: 'black',
                            fontSize: '12',
                            alignment: 'center'
                        };
                        doc.content[0].text = 'Data Export';
                    }
                },
                {
                    extend: 'excel',
                    text: '<i class="mdi mdi-file-excel-outline me-1"></i>Excel',
                    className: 'dropdown-item',
                    exportOptions: {
                        columns: ':not(:last-child)',
                        format: {
                            body: function(inner, coldex, rowdex) {
                                if (inner.length <= 0) return inner;
                                var el = $.parseHTML(inner);
                                var result = '';
                                $.each(el, function(index, item) {
                                    if (item.classList !== undefined && item.classList.contains('user-name')) {
                                        result = result + item.lastChild.firstChild.textContent;
                                    } else if (item.innerText === undefined) {
                                        result = result + item.textContent;
                                    } else result = result + item.innerText;
                                });
                                return result;
                            }
                        }
                    }
                },
                {
                    extend: 'copy',
                    text: '<i class="mdi mdi-content-copy me-1"></i>Copy',
                    className: 'dropdown-item',
                    exportOptions: {
                        columns: ':not(:last-child)',
                        format: {
                            body: function(inner, coldex, rowdex) {
                                if (inner.length <= 0) return inner;
                                var el = $.parseHTML(inner);
                                var result = '';
                                $.each(el, function(index, item) {
                                    if (item.classList !== undefined && item.classList.contains('user-name')) {
                                        result = result + item.lastChild.firstChild.textContent;
                                    } else if (item.innerText === undefined) {
                                        result = result + item.textContent;
                                    } else result = result + item.innerText;
                                });
                                return result;
                            }
                        }
                    }
                }
            ]
        }],
        dom: '<"card-header flex-column flex-md-row"<"head-label text-center"><"dt-action-buttons text-end"B>><"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
        displayLength: 20,
        lengthMenu: [20, 50, 75, 100],
        initComplete: function() {
            $('.dataTables_filter input').css({
                width: '150px',
                height: '35px'
            }).addClass();
        }
    }).buttons().container().appendTo('#example2_wrapper .col-md-6:eq(0)');

    setTimeout(() => {
        $('.dataTables_filter .form-control').removeClass('form-control-sm');
        $('.dataTables_length .form-select').removeClass('form-select-sm');
    }, 300);


});