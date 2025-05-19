<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>Nhật Ký Hoạt Động</title>
     <style>
        /* Tạo hiệu ứng cho form */
        .form-container {
            display: flex;
            flex-direction: column;
            height: 100vh;
            background-color: #f8f9fa;
          
            justify-content: center;
            align-items: center;
        }
        .card {
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            width: 450px;
            background-color: white;
        }
        .card-header {
            background-color: #007bff;
            color: white;
            text-align: center;
            border-radius: 15px 15px 0 0;
            padding: 1px;
        }
        .form-check-label {
            font-size: 1.1rem;
            padding-left: 10px;
        }
        .form-check {
            margin-bottom: 15px;
        }
        .btn-primary {
            background-color: #28a745;
            border-color: #28a745;
            width: 100%;
        }
        .btn-primary:hover {
            background-color: #218838;
            border-color: #1e7e34;
        }
    </style>
</head>
<body>
</body>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function(){
    $('input[name="action"]').change(function(){
        var action = $('input[name="action"]:checked').val();

        if(action === "submit_form" || action === "upload_file"){
            $("#fileInputContainer").show();
        }else{
            $("#fileInputContainer").hide();
        }
    })
})
</script>
</html>
