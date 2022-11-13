
const addProduct = $('.add-product')
const modalProduct = $('#modal-product')
const editbtn = $('.btn-edit')
const deleteBtn = $('.btn-delete')
const sellBtn = $('.btn-sell')
const btnSend = $('#btn-send')
const formProduct = $('form#form-product')
const modalSell = $('#sell_product')
const formSell = $('form#form-sell')
const selectCategory = $("#id_category");

formProduct.on('submit', function(evt) {
    evt.preventDefault();
    updateAddProduct()
})

formSell.on('submit', function(evt) {
    evt.preventDefault();
    sendSell()
})

const getCategory = async () => {
    try {
        const rawData = await fetch(`./sql_modules/views/view_category.php`, {
            method: 'GET',
            headers: {
              'Content-Type': 'application/json'
            }
        })
        const data = await rawData.json()
        $.each(data.data, function(i, c) {
            console.log(c.id);
            selectCategory.append($("<option>", {
                value: c.id,
                text: c.name
            }));
        })
        
    } catch (error) {
        alert(error)
    }
}

getCategory()

const sendSell = async () => {
    try {
        const rawData = await fetch(`./sql_modules/views/view_product.php`, {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json'
            },
            body : JSON.stringify({
                "action" : "SELL",
                "id" : $('#id_product_sell').val(),
                "amount": $('#amount').val() 
            }) 
          })
          const data = await rawData.json()
          getProducts()
          alert (data.data.reason)
    } catch (error) {
        alert (error)
    }
}

function update(id) {
    btnSend.attr('data-action', 'UPDATE') 
    getProducts(id, true)
}

const sell = async (id) => {
    $('#id_product_sell').val(id)
    var modalV = new bootstrap.Modal(modalSell, {
        keyboard: false
    })
    modalV.toggle()
}


const _delete = async (id) => {
    try {
        const rawData = await fetch(`./sql_modules/views/view_product.php?id=${id}`, {
            method: 'DELETE',
            headers: {
              'Content-Type': 'application/json'
            },
            
          })
          const data = await rawData.json()
          if (data.data.message) {
            alert(data.data.message)
            getProducts()
          } else {
            alert (data.data.reason)
          }
          
    } catch (error) {
       alert (error) 
    }  
}


addProduct.click (() => {
    btnSend.attr('data-action', 'SAVE')
    var modalP = new bootstrap.Modal(modalProduct, {
        keyboard: false
    })
    modalP.toggle()
})

const updateAddProduct = async () => {
    method = 'POST'
    action = btnSend.attr('data-action')

    if (action == 'UPDATE') {
        method = 'PUT'
    }
    formdata = new FormData(document.getElementById("form-product"))
    console.error(formdata);
    dataSend = {
        "action": action,
        "id":formdata.get("id"),
        "name":formdata.get("name"),
        "reference":formdata.get("reference"),
        "price":formdata.get("price"),
        "stock":formdata.get("stock"),
        "id_category":formdata.get("id_category"),
        "weight": formdata.get("weight")
    } 
    try {
        const rawData = await fetch(`./sql_modules/views/view_product.php`, {
            method: method,
            headers: {
              'Content-Type': 'application/json'
            },
            body:JSON.stringify(dataSend) 
          })
          const data = await rawData.json()
          if (data.data.message != null) {
            alert(data.data.message)
            if (action == 'SAVE') {
               formProduct[0].reset(); 
            }
            getProducts()
          }else{
             alert(data.data.reason)
          }
    } catch (error) {
        alert(error)
    }
}

const getProducts  = async (id = '', edit = false) => {
    try {
        const rawData = await fetch(`./sql_modules/views/view_product.php?id=${id}`, {
            method: 'GET',
            headers: {
              'Content-Type': 'application/json'
            },
            
          })
          const data = await rawData.json()
          console.log(data.data);
          if (data.data.reason == null) {
              if (!edit) {
                mapTable(data.data)
              } else { 
                mapForm(data.data[0])
              }
              
              
          }else{
            alert(data.data.reason)
            
          }

    } catch (error) {
        alert(`ha ocurrido un error al hacer la peticion ${error}`)
        
    }
}

const mapForm = async (data) => {
    console.log(data.id);
    $('#id').val(data.id)
    $('#name').val(data.name)
    $('#reference').val(data.reference)
    $('#stock').val(data.stock)
    $('#weight').val(data.weight)
    $('#category').val(data.category)
    $('#price').val(data.price)

    var modalP = new bootstrap.Modal(modalProduct, {
        keyboard: false
    })
    modalP.toggle()
    
}

const mapTable = async (data) => {     
    row = ''
    $.each(data, function(i, p) {
        $actions = `<img class='btn-icon btn-delete' data-id='${p.id}' onclick="_delete(${p.id})" src='./pages/assets/img/delete.png' alt='Eliminar' title='Eliminar' width="30" height="30">`
        $actions += `<img class='btn-icon btn-edit' data-id='${p.id}' onclick="update(${p.id})" src='./pages/assets/img/update.png' alt='Editar' title='Editar' width="30" height="30">`
        $actions += `<img class='btn-icon btn-sell' data-id='${p.id}' onclick="sell(${p.id})"  src='./pages/assets/img/sell.png' alt='sell' title='Vender' width="30" height="30">`
        row += `<tr><th>${p.id}</th><th>${p.name}</th><th>${p.reference}</th><th>${p.price}</th><th>${p.weight}</th><th>${p.category}</th><th>${p.stock}</th><th>${p.creation_date}</th><th>${$actions}</th></tr>`

    });
    $('#product-table tbody').html('') 
    $('#product-table tbody').append(row) 
}

getProducts()
