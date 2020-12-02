<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>MBImages</title>
  <link href="https://getbootstrap.com/docs/4.5/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
  .mt-50 {
    margin-top: 50px;
  }
  </style>
</head>
<body>
  <div class="container mt-50">
    <div class="row">
      <div class="col-md-6 offset-md-3">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Upload Image</h5>
            <form method="POST" action="{{ url('mbimage') }}" enctype="multipart/form-data">
              @csrf
              <div class="form-group">
                <label for="iamge-file">Image File</label>
                <input type="file" class="form-control-file" id="iamge-file" name="image">
              </div>
              <button type="submit" class="btn btn-primary">Submit</button>
            </form>
          </div>
        </div>
      </div>
    </div>
    @if(!empty($images))
    <div class="row">
      <div class="col-lg-12 text-center">
        <h1>Uploaded Image</h1>
      </div>
      <div class="col-lg-4 text-center">
        <img src="{{ $images->thumbnail_size }}" class="img-fluid">
        <div class="text-center">
          <a href="{{ $images->thumbnail_size }}" class="btn btn-primary btn-sm" target="_blank">Thumbnail Size</a>
        </div>
      </div>
      <div class="col-lg-4 text-center">
        <img src="{{ $images->small_size }}" class="img-fluid">
        <div class="text-center">
          <a href="{{ $images->small_size }}" class="btn btn-primary btn-sm" target="_blank">Small Size</a>
        </div>
      </div>
      <div class="col-lg-4 text-center">
        <img src="{{ $images->full_size }}" class="img-fluid">
        <div class="text-center">
          <a href="{{ $images->full_size }}" class="btn btn-primary btn-sm" target="_blank">Full Size</a>
        </div>
      </div>
    </div>
    @endif
  </div>
</body>
</html>