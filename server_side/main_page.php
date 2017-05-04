<?php
include("header.php");
?>

<body>
<div id="logout-editdetails-navbar" class="navbar-collapse collapse">
    <form id="logout-form" class="navbar-form navbar-right" role="form" action="index.php" method="POST">
        <input type="hidden" name="logout" value="true">
        <button type="submit" id="logout" class="btn btn-danger"  style="background-color: #d9534f !important"><i class="glyphicon glyphicon-off"></i></button>
    </form>
    <button type="button" id="edit-user" class="btn navbar-btn navbar-right" style="margin-top: 0.52em; background-color: #5cb85c !important" data-toggle="modal" data-target="#edit-user-modal">
        <i class="glyphicon glyphicon-user"></i>
    </button>
</div>


<div class="container">
    <div id="toolbar" class="btn-group">
        <select id="display-folder" class="selectpicker" data-style="btn-primary" data-show-icon="true">
            <option value="all" default>All</option>
           <?php
            $query = "SELECT name from folders WHERE user_id = ?";
            $queryParams = array($_SESSION['user_id']);
            $results = $db -> executeSelectAll($query, $queryParams);
            
            $currentFolder = 'all';
            if(isset($_GET['folder'])) {
              $currentFolder = $_GET['folder'];
            }

            foreach($results as $row) { 
                if($currentFolder===$row['name']) {
                     echo "<option selected='selected' value='".$row['name']."'>".$row['name']."</option>";
                }
                else {
                     echo "<option value='".$row['name']."'>".$row['name']."</option>";
                }
            } ?>
        </select>
        <button type="button" id="add-folder" class="btn btn-default" data-toggle="modal" data-target="#add-folder-modal">
            <i class="glyphicon glyphicon-plus"></i>
        </button>
       
        <?php if (isset($_GET['folder']) && $_GET['folder'] !== 'all' && $_GET['folder'] !== 'trash' && $_GET['folder'] !== 'unfiled'){ ?>
        <button type="button" id="remove-folder" class="btn btn-default" data-toggle="modal" data-target="#remove-folder-modal">
            <i class="glyphicon glyphicon-trash"></i>
        </button>
        <button type="button" id="rename-folder" class="btn btn-default" data-toggle="modal" data-target="#rename-folder-modal">
            <i class="glyphicon glyphicon-edit"></i>
        </button>
        <?php } ?>
    </div>
    <table class="table table-striped table-responsive table-hover row" id="table"
           data-search="true"
           data-toolbar="#toolbar"
           data-toggle="toggle"
           data-show-columns="true"
           data-show-export="true"
           data-minimum-count-columns="2"
           data-show-pagination-switch="true"
           data-pagination="true"
           data-id-field="id"
           data-page-list="[10, 25, 50, 100, ALL]"
           data-show-footer="false"
           data-side-pagination="server"
           data-response-handler="responseHandler">
    </table>
    <div id="refs-toolbar" class="col-lg-12">
        <div class="row">
            <div class="col-xs-4">
              <button type="button" id="add-new-ref"  class="btn btn-info btn-lg open-AddReferenceDialog" data-toggle="modal" data-target="#add-new-ref-modal">Add new Reference</button>
            </div>
            <div class="col-xs-4">
                <select id="destination-folder" name="destination-folder" class="selectpicker" data-style="btn-primary" data-show-icon="true">
                    <option value="Move References" style="display: none;" disabled selected>Move References</option>
                    <?php
                        $query = "SELECT name from folders WHERE user_id = ?";
                        $queryParams = array($_SESSION['user_id']);
                        $results = $db -> executeSelectAll($query, $queryParams);

                        foreach($results as $row) { 
                            echo "<option value='".$row['name']."'>".$row['name']."</option>";
                        }
                    ?>
                </select>
            </div>
            <div class="col-xs-4">
              <button type="button" id="delete-refs" class="btn btn-info btn-lg">Delete References</button>
            </div>
        </div>
    </div>
  <!-- Modal -->
  <div class="modal fade" id="add-new-ref-modal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Add new Reference</h4>
        </div>
        
        <form role="form" id="add-new-ref-form" class="add-new-ref-form" action="" method="post">
            <div class="modal-body">
                <select id="folder" name="folder" class="form-control" style="min-width: 100%" required>
                        <option value="" default>Select a Folder...</option>
                       <?php
                        $query = "SELECT name from folders WHERE user_id = ?";
                        $queryParams = array($_SESSION['user_id']);
                        $results = $db -> executeSelectAll($query, $queryParams);

                        foreach($results as $row) { 
                            if(isset($_GET['folder']) && $_GET['folder']===$row['name']) {
                                 echo "<option selected='selected' value='".$row['name']."'>".$row['name']."</option>";
                            }
                            else {
                                 echo "<option value='".$row['name']."'>".$row['name']."</option>";
                            }
                        } ?>
                </select>
                <input id="title" name="title"  type="text" placeholder="Title..." style="min-width: 100%" required/>
                <input id="author" name="author" type="text" placeholder="Author..." style="min-width: 100%" required/>
                <input id="url" name="url" type="url" placeholder="URL..." style="min-width: 100%" required/>
                <div class="form-group">
                    <div class='input-group date' id='date_added_div'>
                        <input type="text" id="date_added" name="date_added" class="form-control" placeholder="YYYY-MM-DD" style="min-width: 100%"/>
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                    </div>
                </div>
                <input id="year_published" name="year_published" class="form-control" type="number" min="1900" max="2017" placeholder="Publish year..." style="min-width: 100%" required/>
                <input id="pages" name="pages" class="form-control" type="number" min="1" max="999999" placeholder="Number of Pages" style="min-width: 100%"/>
                <input id="volume" name="volume" class="form-control" type="number" min="1" max="999" placeholder="Volume Number" style="min-width: 100%"/>
                <input id="abstract" name="abstract" class="form-control" type="text" placeholder="Abstract..." style="min-width: 100%" />
            </div>
            <div class="modal-footer">
              <button type="submit" class="btn">Add Reference</button>
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </form>
      </div>
      
    </div>
  </div>


  <!-- Modal -->
  <div class="modal fade" id="add-folder-modal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Add new Folder</h4>
        </div>
        
        <form role="form" id="add-new-folder-form" class="add-new-folder-form" action="" method="post">
            <div class="modal-body">
                <input id="folder_name" name="folder_name"  type="text" placeholder="Enter folder name..." style="min-width: 100%" required/>
            </div>
            <div class="modal-footer">
              <button type="submit" class="btn">Add Folder</button>
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </form>
      </div>
      
    </div>
  </div>


    <!-- Modal -->
  <div class="modal fade" id="remove-folder-modal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Remove <?php echo $_GET['folder']; ?> Folder</h4>
        </div>
        
        <form role="form" id="remove-folder-form" class="remove-folder-form" action="" method="post">
            <div class="modal-body">
                <input type="hidden" id="deleted-folder" name="deleted-folder" value="<?php echo $_GET['folder']; ?>">
                <select id="refs-destination-folder" name="refs-destination-folder" class="form-control" style="min-width: 100%" required>
                        <option value="Move References" style="display: none;" disabled selected>Move References to...</option>
                        <?php
                            $query = "SELECT name from folders WHERE user_id = ?";
                            $queryParams = array($_SESSION['user_id']);
                            $results = $db -> executeSelectAll($query, $queryParams);

                            foreach($results as $row) { 
                                echo "<option value='".$row['name']."'>".$row['name']."</option>";
                            }
                        ?>
                </select>
            </div>
            <div class="modal-footer">
              <button type="submit" class="btn">Confirm</button>
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </form>
      </div>
      
    </div>
  </div>

    <!-- Modal -->
  <div class="modal fade" id="rename-folder-modal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Rename <?php echo $_GET['folder']; ?> Folder</h4>
        </div>
        
        <form role="form" id="rename-folder-form" class="rename-folder-form" action="" method="post">
            <div class="modal-body">
                <input type="hidden" id="renamed-folder" name="renamed-folder" value="<?php echo $_GET['folder']; ?>">
                <input id="new-name" name="new-name" type="text" placeholder="Type new name here..." style="min-width: 100%" required/>

            </div>
            <div class="modal-footer">
              <button type="submit" class="btn">Rename</button>
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </form>
      </div>
      
    </div>
  </div>


      <!-- Modal -->
  <div class="modal fade" id="edit-user-modal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Edit User Details</h4>
        </div>
        
        <form role="form" id="edit-user-form" class="edit-user-for" action="" method="post">
            <div class="modal-body">
               <?php
                $query = "SELECT u.* FROM user u WHERE u.id = ? LIMIT 1";
                $queryParams = array($_SESSION['user_id']);
                $user_details = $db -> executeSelectOne($query, $queryParams);
                ?>
                <input id="uname" name="uname" type="email" placeholder="Username..." class="form-control" required value=<?=$user_details['uname']?>>
                <input type="password" id="old_password" value="" name="old_password" placeholder="Current password..." class="form-control">
                <div style="display:none" id="wrong_password" class="alert-warning">
                    <h3>Wrong password!</h3>
                </div>
                <input type="password" id="new_password" value="" name="new_password" placeholder="New password..." data-rule-notEqualTo="#old_password" class="form-control">
                <input type="password" id="password_confirm" value="" name="password_confirm" data-rule-equalTo="#new_password" placeholder="Confirm Password..." class="form-control">
                <textarea id="user_bio" name="user_bio" placeholder="About yourself..." class="form-control" value=<?=$user_details['user_bio']?>><?=$user_details['user_bio']?></textarea>
            </div>
            <div class="modal-footer">
              <button type="submit" class="btn">Confirm</button>
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </form>
      </div>
      
    </div>
  </div>

</div>

<script>
    var $table = $('#table'),
        $remove = $('#delete-refs'),
        selections = [];
    function initTable() {
        var list_refs = $.parseJSON('<?php echo getRefs(); ?>');
        console.log(list_refs);
        $table.bootstrapTable({
            data: list_refs,
            height: getHeight(),
            columns: [
                [
                    {
                        field: 'state',
                        checkbox: true,
                        align: 'center',
                        valign: 'middle'
                    }, {
                        field: 'id',
                        visible: false
                    }, {
                        field: 'title',
                        title: 'Title',
                        sortable: true,
                         editable: {
                            type: 'text',
                            title: 'Title',
                            validate: function (value) {
                                value = $.trim(value);
                                if (!value) {
                                    return 'This field is required';
                                }
                                var data = $table.bootstrapTable('getData'),
                                    index = $(this).parents('tr').data('index');
                                return '';
                            }
                        },
                        align: 'center'
                    }, {
                        field: 'author',
                        title: 'Author',
                        sortable: true,
                        align: 'center',
                        editable: {
                            type: 'text',
                            title: 'Author',
                            validate: function (value) {
                                value = $.trim(value);
                                if (!value) {
                                    return 'This field is required';
                                }
                                var data = $table.bootstrapTable('getData'),
                                    index = $(this).parents('tr').data('index');
                                return '';
                            }
                        }
                    }, {
                        field: 'date_added',
                        title: 'Date Added',
                        sortable: true,
                        editable: {
                            type: 'date',
                            format : 'yyyy-mm-dd',
                            viewformat : 'yyyy-mm-dd',
                            inputclass : "datepick",
                            placement: function (context, source) {
                              var popupWidth = 336;
                              if(($(window).scrollLeft() + popupWidth) > $(source).offset().left){
                                return "right";
                              } else {
                                return "left";
                              }
                            },
                            emptytext: '...',
                            datetimepicker : {
                              weekStart : 1
                            },
                            title: 'Date Added',
                            validate: function (value) {
                                value = $.trim(value);
                                if (!value) {
                                    return 'This field is required';
                                }
                                var data = $table.bootstrapTable('getData'),
                                    index = $(this).parents('tr').data('index');
                                return '';
                            }
                        },
                        align: 'center'
                    }, {
                        field: 'year_published',
                        title: 'Year Published',
                        sortable: true,
                        editable: {
                            type: 'number',
                            title: 'Year Published',
                            validate: function (value) {
                                value = $.trim(value);
                                if (!value) {
                                    return 'This field is required';
                                }
                                var data = $table.bootstrapTable('getData'),
                                    index = $(this).parents('tr').data('index');
                                return '';
                            }
                        },
                        align: 'center'
                    }, {
                        field: 'pdf_url',
                        title: 'URL',
                        editable: {
                            type: 'url',
                            title: 'URL',
                            validate: function (value) {
                                value = $.trim(value);
                                if (!value) {
                                    return 'This field is required';
                                }
                                var data = $table.bootstrapTable('getData'),
                                    index = $(this).parents('tr').data('index');
                                return '';
                            }
                        },
                        align: 'center'
                    }, {
                        field: 'volume',
                        title: 'Volume',
                         editable: {
                            type: 'number',
                            title: 'Volume'
                        },
                        align: 'center'
                    }, {
                        field: 'abstract',
                        title: 'Abstract',
                        editable: true,
                        align: 'center'
                    }, {
                        field: 'pages',
                        title: 'Pages',
                        sortable: true,
                        editable: {
                            type: 'number',
                            title: 'Pages'
                        },
                        align: 'center'
                    }, {
                        field: 'operate',
                        title: 'Actions',
                        align: 'center',
                        events: operateEvents,
                        formatter: operateFormatter
                    }
                ]
            ]
        });
        // sometimes footer render error.
        setTimeout(function () {
            $table.bootstrapTable('resetView');
        }, 200);
        $table.on('check.bs.table uncheck.bs.table ' +
                'check-all.bs.table uncheck-all.bs.table', function () {
            $remove.prop('disabled', !$table.bootstrapTable('getSelections').length);
            // save your data, here just save the current page
            selections = getIdSelections();
            // push or splice the selections if you want to save all data selections
        });
        $table.on('all.bs.table', function (e, name, args) {
            console.log(name, args);
        });
        $remove.click(function (e) {
            var ids = getIdSelections();
            e.preventDefault();         

            var request = $.ajax({
              url: "../ajax/moveRefs.php",
              method: "POST",
              data: { ids : ids, newFolder : 'trash' },
              dataType: "html"
            });
             
            request.done(function( msg ) {
                if(msg == 'true') {
                    location.reload();
                } else if(msg == 'false') {
                    alert('Ooops! Something went wrong!');
                }
            });
             
            request.fail(function( jqXHR, textStatus ) {
              alert( "Request failed: " + textStatus );
            });
        });
        $(window).resize(function () {
            $table.bootstrapTable('resetView', {
                height: getHeight()
            });
        });
    }
    function getIdSelections() {
        return $.map($table.bootstrapTable('getSelections'), function (row) {
            return row.id
        });
    }
    function responseHandler(res) {
        $.each(res, function (i, row) {
            row.state = $.inArray(row.id, selections) !== -1;
            console.log(row.state);
        });
        return res;
    }
    function operateFormatter(value, row, index) {
        return [
            '<a class="save" href="javascript:void(0)" title="Save">',
            '<i class="glyphicon glyphicon-floppy-save"></i>',
            '</a> ',
            '<a class="remove" href="javascript:void(0)" title="Remove">',
            '<i class="glyphicon glyphicon-remove"></i>',
            '</a>'
        ].join('');
    }
    window.operateEvents = {
        'click .remove': function (e, value, row, index) {
            var ids = [row.id];
            e.preventDefault();         

            var request = $.ajax({
              url: "../ajax/moveRefs.php",
              method: "POST",
              data: { ids : ids, newFolder : 'trash'},
              dataType: "html"
            });
             
            request.done(function( msg ) {
                if(msg == 'true') {
                    location.reload();
                } else if(msg == 'false') {
                    alert('Ooops! Something went wrong!');
                }
            });
             
            request.fail(function( jqXHR, textStatus ) {
              alert( "Request failed: " + textStatus );
            });
        },

        'click .save': function (e, value, row, index) {
            e.preventDefault();         
            var request = $.ajax({
              url: "../ajax/updateRef.php",
              method: "POST",
              data: { id : row.id, title : row.title, author : row.author, pdf_url : row.pdf_url, date_added : row.date_added, year_published : row.year_published, pages : row.pages, volume : row.volume, abstract : row.abstract},
              dataType: "html"
            });
             
            request.done(function( msg ) {
                if(msg == 'true') {
                    location.reload();
                } else if(msg == 'false') {
                    alert('Ooops! Something went wrong!');
                }
            });
             
            request.fail(function( jqXHR, textStatus ) {
              alert( "Request failed: " + textStatus );
            });
        }
    };
    function totalTextFormatter(data) {
        return 'Total';
    }
    function totalNameFormatter(data) {
        return data.length;
    }
    function getHeight() {
        return $(window).height() - $('#refs-toolbar').outerHeight(true) - $('#logout-editdetails-navbar').outerHeight(true);
    }
    $(function () {
        var scripts = [
                '../js/vendor/bootstrap-table.min.js',
                '../js/vendor//bootstrap-table-export.min.js',
                'http://rawgit.com/hhurz/tableExport.jquery.plugin/master/tableExport.js',
                '../js/vendor/bootstrap-table-editable.min.js',
                'http://rawgit.com/vitalets/x-editable/master/dist/bootstrap3-editable/js/bootstrap-editable.js'
            ],
            eachSeries = function (arr, iterator, callback) {
                callback = callback || function () {};
                if (!arr.length) {
                    return callback();
                }
                var completed = 0;
                var iterate = function () {
                    iterator(arr[completed], function (err) {
                        if (err) {
                            callback(err);
                            callback = function () {};
                        }
                        else {
                            completed += 1;
                            if (completed >= arr.length) {
                                callback(null);
                            }
                            else {
                                iterate();
                            }
                        }
                    });
                };
                iterate();
            };
        eachSeries(scripts, getScript, initTable);
    });
    function getScript(url, callback) {
        var head = document.getElementsByTagName('head')[0];
        var script = document.createElement('script');
        script.src = url;
        var done = false;
        // Attach handlers for all browsers
        script.onload = script.onreadystatechange = function() {
            if (!done && (!this.readyState ||
                    this.readyState == 'loaded' || this.readyState == 'complete')) {
                done = true;
                if (callback)
                    callback();
                // Handle memory leak in IE
                script.onload = script.onreadystatechange = null;
            }
        };
        head.appendChild(script);
        // We handle everything using the script element injection
        return undefined;
    }
</script>

<script type="text/javascript">
    window.onload=function(){
        $('.selectpicker').selectpicker();
    };

    $(function () {
        $('#date_added_div').datetimepicker({
            format: 'YYYY-MM-DD'
        });
    });    

    $('#display-folder').change( function (e) {
        
        e.preventDefault();
        var folder = $(this).val();
        window.location.href = "main_page.php?folder="+folder;   
    });

    $('#destination-folder').change( function (e) {
        
        e.preventDefault();
        var newFolder = $('#destination-folder').val();
        var ids = getIdSelections();
        e.preventDefault();         

        if(ids.length === 0) {
            $("#destination-folder option[value='Move References']").prop('selected', true);
            return false;
        }
        
        var request = $.ajax({
          url: "../ajax/moveRefs.php",
          method: "POST",
          data: { ids : ids, newFolder : newFolder },
          dataType: "html"
        });
         
        request.done(function( msg ) {
            if(msg == 'true') {
                location.reload();
            } else if(msg == 'false') {
                alert('Ooops! Something went wrong!');
            }
        });
         
        request.fail(function( jqXHR, textStatus ) {
          alert( "Request failed: " + textStatus );
        });
        
    });

    $(document).ready(function () {
        $('#add-new-ref-form').on('submit', function(e){
            e.preventDefault();         
            $info = $('#add-new-ref-form').serialize();

            var request = $.ajax({
              url: "../ajax/addNewRef.php",
              method: "POST",
              data: $info,
              dataType: "html"
            });
             
            request.done(function( msg ) {
                if(msg == 'true') {
                    location.reload();
                }
                else if(msg == 'false') {
                    alert('Ooops! Something went wrong!');
                }

            });
             
            request.fail(function( jqXHR, textStatus ) {
              alert( "Request failed: " + textStatus );
            });
       });


        $('#add-new-folder-form').on('submit', function(e){
            e.preventDefault();         
            $info = $('#add-new-folder-form').serialize();

            var request = $.ajax({
              url: "../ajax/addNewFolder.php",
              method: "POST",
              data: $info,
              dataType: "html"
            });
             
            request.done(function( msg ) {
                if(msg == 'true') {
                    location.reload();
                }
                else if(msg == 'false') {
                    alert('Ooops! Something went wrong!');
                }

            });
             
            request.fail(function( jqXHR, textStatus ) {
              alert( "Request failed: " + textStatus );
            });
       });

        $('#remove-folder-form').on('submit', function(e){
            e.preventDefault();         
            $info = $('#remove-folder-form').serialize();

            var request = $.ajax({
              url: "../ajax/removeFolder.php",
              method: "POST",
              data: $info,
              dataType: "html"
            });
             
            request.done(function( msg ) {
                if(msg == 'true') {
                    window.location.href = "main_page.php";
                }
                else if(msg == 'false') {
                    alert('Ooops! Something went wrong!');
                }

            });
             
            request.fail(function( jqXHR, textStatus ) {
              alert( "Request failed: " + textStatus );
            });
       });

        $('#rename-folder-form').on('submit', function(e){
            e.preventDefault();         
            $info = $('#rename-folder-form').serialize();

            var request = $.ajax({
              url: "../ajax/renameFolder.php",
              method: "POST",
              data: $info,
              dataType: "html"
            });
             
            request.done(function( msg ) {
                if(msg == 'true') {
                    window.location.href = "main_page.php?folder=" + $('#rename-folder-form input[name="new-name"]').val();
                }
                else if(msg == 'false') {
                    alert('Ooops! Something went wrong!');
                }

            });
             
            request.fail(function( jqXHR, textStatus ) {
              alert( "Request failed: " + textStatus );
            });
       });


        $('#edit-user-form').validate();   
        $('#edit-user-form').on('submit', function(e){
            e.preventDefault();         
            $info = $('#edit-user-form').serialize();

            var request = $.ajax({
              url: "../ajax/editUserDetails.php",
              method: "POST",
              data: $info,
              dataType: "html"
            });
             
            request.done(function( msg ) {
                alert(msg);
                if(msg == 'true') {
                    location.reload();
                }
                else if(msg == 'false') {
                    $('#wrong_password').show();
                }

            });
             
            request.fail(function( jqXHR, textStatus ) {
              alert( "Request failed: " + textStatus );
            });
       });
    });
</script>
</body>
</html>