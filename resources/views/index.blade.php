<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
        integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous">
    </script>
</head>

<body>
    <div class="container">

        <div id="list-category">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th scope="col">Id</th>
                        <th scope="col">Category Name</th>
                        <th scope="col">Category Status</th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody id="table-category">

                </tbody>
            </table>
        </div>

        {{-- <div class="list-group" id="list-category">

        </div> --}}

        <form action="" method="POST" role="form" id="formAdd">
            @csrf
            <div class="form-group">
                <label for="">Tên danh mục</label>
                <input type="text" class="form-control" name="categoryName" placeholder="">
            </div>
            <div class="form-group">
                <label for="">Trạng thái</label>
                <input type="text" class="form-control" name="categoryStatus" placeholder="">
            </div>
            <button type="submit" class="btn btn-primary">Gửi</button>
        </form>
    </div>

    <script src="https://cdn-script.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <script>
        loadData();

        function loadData() {
            // $.ajax({
            //     url: 'http://localhost:8000/api/category',
            //     type: 'GET',
            //     dataType: 'json',
            //     data: null,
            //     success: function(data, textStatus, xhr) {
            //         if (data.status == 200) {
            //             let categories = data.data;
            //             let _li = '';
            //             categories.forEach(function(item) {
            //                 _li += '<a href="#" class="list-group-item">' + item.category_name + '</a>';
            //             });
            //             $('#list-category').html(_li);
            //         }
            //     },
            //     error: function(xhr, textStatus, errorThrown) {
            //         console.log('Error in Operation');
            //     }
            // });

            $.ajax({
                url: 'http://localhost:8000/api/category',
                type: 'GET',
                dataType: 'json',
                data: null,
                success: function(data, textStatus, xhr) {
                    if (data.status == 201) {
                        let categories = data.data;
                        let html = '';
                        categories.forEach(function(item) {
                            html += '<tr> <td>' + item.id + '</td>';
                            html += '<td>' + item.category_name + '</td>';
                            html += '<td>' + (item.category_status == 0 ? 'Không hoạt động' :
                                'Hoạt động') + '</td>';
                            html += '<td> <a href="" class="btn btn-sm btn-primary">Sửa</a>';
                            html += '<a href="/api/category/delete/' + item.id +
                                '" class="btn btn-sm btn-primary btn-delete">Xóa</a> </td> </tr>';
                        });
                        $('#table-category').html(html);
                    } else {
                        alert(data.message);
                    }
                },
                error: function(xhr, textStatus, errorThrown) {
                    console.log('Error in Operation');
                }
            });
        }

        $('#formAdd').on('submit', function(e) {
            e.preventDefault();
            let formData = $('#formAdd').serialize();
            //alert(formData);
            $.ajax({
                url: 'http://localhost:8000/api/category',
                type: 'POST',
                dataType: 'json',
                data: formData,
                success: function(data, textStatus, xhr) {
                    if (data.status == 201) {
                        loadData();
                    } else {
                        alert(data.message);
                    }
                },
                error: function(xhr, textStatus, errorThrown) {
                    console.log('Error in Operation');
                }
            });
        });

        $(document).on('click', '.btn-delete', function(e) {
            e.preventDefault();
            let href = new URL(window.location.href).origin + $(this).attr('href');
            let formData = $('#formAdd').serialize();
            $.ajax({
                url: href,
                type: 'GET',
                dataType: 'json',
                data: formData,
                success: function(data, textStatus, xhr) {
                    if (data.status == 201) {
                        loadData();
                    } else {
                        alert(data.message);
                    }
                },
                error: function(xhr, textStatus, errorThrown) {
                    console.log('Error in Operation');
                }
            });
        })
    </script>
</body>

</html>
