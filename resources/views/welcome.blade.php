<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Productos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" integrity="sha512-vKMx8UnXk60zUwyUnUPM3HbQo8QfmNx7+ltw8Pm5zLusl1XIfwcxo8DbWCqMGKaWeNxWA8yrx5v3SaVpMvR3CA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
    <div class="container">
        <div class="card">
            <div class="card-header text-center">
                <h3>
                    Control de Stock
                </h3>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between px-5 mb-2">
                    <h5 class="card-title">Listado</h5>
                    <a type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalProduct">Agregar <i class="fa fa-plus-circle"></i></a>
                </div>
                <table class="table table-light table-striped">
                    <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th class="text-center">Descripcion</th>
                            <th class="text-center">Categoria</th>
                            <th class="text-center">Cantidad</th>
                            <th class="text-center">Estado</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="table-detail"></tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="modalProduct" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalProductTitle" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalProductTitle">Agregar Producto</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger" id="errors" style="display:none;">
                        <ul id="errorlist"></ul>
                    </div>
                    <form id="form">
                        <input type="text" id="productId" hidden>
                        <div class="form-group mb-3">
                            <label for="description">Descripcion *</label>
                            <input type="text" class="form-control" id="description" name="description" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="category">Categoria </label>
                            <input type="text" class="form-control" id="category" name="category">
                        </div>
                        <div class="form-group mb-3">
                            <label for="quantity">Cantidad *</label>
                            <input type="number" class="form-control" id="quantity" name="quantity" required>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="status" name="status">
                            <label class="form-check-label" for="status">Estado</label>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="save()">Guardar</button>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js" integrity="sha512-fD9DI5bZwQxOi7MhYWnnNPlvXdp/2Pj3XSTRrFs5FQa4mizyGLnJcN6tuvUS6LbmgN1ut+XGSABKvjN0H6Aoow==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        let editMode = false
        let url = 'http://proyecto-api.test'
        const products = () => {
            $.ajax({
                url: url + '/api/products',
                method: 'GET',
                dataType: 'json',
                success: function (data) {
                    if (data.length) {
                        $('#table-detail').empty()
                        $.each(data, function (index, element) {
                            $('#table-detail').append(`
                                <tr>
                                    <td class="text-center">${element.id}</td>
                                    <td class="text-center">${element.description}</td>
                                    <td class="text-center">${element.category}</td>
                                    <td class="text-center">${element.quantity}</td>
                                    <td class="text-center">${element.status ? '<i class="fa fa-check"></i> ' : ''}</td>
                                    <td class="text-start">
                                        <button class="btn btn-warning btn-sm" onclick="edit('${element.id}')">
                                            <i class="fa fa-pencil"></i>
                                        </button>
                                        <button class="btn btn-danger btn-sm" onclick="destroy('${element.id}')" ${element.status ? '' : 'hidden'}>
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            `)
                        })
                    }
                },
                error: function (xhr, status, error) {
                    console.log(error);
                }
            })
        }
        products()

        const clean = () => {
            editMode = false
            $('#errorlist').empty()
            $('#errors').hide()
            $("#form")[0].reset();
        }

        const save = () => {
            var formData = $("#form").serialize();

            if (editMode) {
                let id=$('#productId').val()
                $.ajax({
                    url: url + '/api/products/'+id,
                    type: "PUT",
                    data: formData,
                    success: function (response) {
                        products()
                        toastr.success(response.message)
                        clean()
                        $('#modalProduct').modal('toggle')
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        $('#errorlist').empty()
                        $.each(jqXHR.responseJSON.errors, function (index, element) {
                            $('#errorlist').append(`
                                <li>${element}</li>
                            `)
                        })
                        $('#errors').show(300)
                    }
                });
            } else {
                $.ajax({
                    url: url + '/api/products',
                    type: "POST",
                    data: formData,
                    success: function (response) {
                        products()
                        toastr.success(response.message)
                        clean()
                        $('#modalProduct').modal('toggle')
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        $('#errorlist').empty()
                        $.each(jqXHR.responseJSON.errors, function (index, element) {
                            $('#errorlist').append(`
                                <li>${element}</li>
                            `)
                        })
                        $('#errors').show(300)
                    }
                });
            }
        }

        const edit = (id) => {
            editMode = true
            $.ajax({
                url: url + '/api/products/' + id,
                type: "GET",
                success: function (response) {
                    $('#modalProduct').modal('toggle')
                    $('#productId').val(id)
                    $('#description').val(response.description)
                    $('#category').val(response.category)
                    $('#quantity').val(response.quantity)
                    $('#status').prop("checked",response.status)
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    toastr.error(jqXHR.responseJSON.message)
                }
            });

        }

        const destroy = (id) => {
            if (confirm('¿Seguro que desea dar de baja?')) {
                $.ajax({
                    url: url + '/api/products/' + id,
                    type: "DELETE",
                    success: function (response) {
                        products()
                        toastr.success(response.message)
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        toastr.error(jqXHR.responseJSON.message)
                    }
                });
            }
        }

    </script>
</body>

</html>
