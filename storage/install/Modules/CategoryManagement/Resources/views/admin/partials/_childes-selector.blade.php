<select class="js-select theme-input-style w-100" name="sub_category_id">
    @foreach($categories as $category)
        <option value="{{$category->id}}" {{isset($sub_category_id) && $sub_category_id == $category->id ? 'selected' : ''}}>{{$category->name}}</option>
    @endforeach
</select>

<script>
    $(document).ready(function () {
        $('.js-select').select2();
    });
</script>
