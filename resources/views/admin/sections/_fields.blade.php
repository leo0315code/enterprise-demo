<input type="hidden" name="section_id" value="{{ $section->id }}">
<select name="type">
    @foreach(['hero'=>'Hero Banner','intro'=>'公司简介','features'=>'核心优势（卡片）','products'=>'推荐产品','news'=>'最新新闻','cta'=>'行动召唤','custom'=>'自定义内容'] as $val=>$label)
        <option value="{{ $val }}" {{ $section->type == $val ? 'selected' : '' }}>{{ $label }}</option>
    @endforeach
</select>
<input type="number" name="sort" value="{{ old('sort', $section->sort ?? 0) }}">
<input type="text" name="title" value="{{ old('title', $section->title ?? '') }}">
<input type="text" name="subtitle" value="{{ old('subtitle', $section->subtitle ?? '') }}">
<input type="text" name="button_text" value="{{ old('button_text', $section->button_text ?? '') }}">
<input type="text" name="button_link" value="{{ old('button_link', $section->button_link ?? '') }}">
<input type="text" name="image" value="{{ old('image', $section->image ?? '') }}">
<textarea name="content">{{ old('content', $section->content ?? '') }}</textarea>
<textarea name="extra">{{ old('extra', $section->extra ? json_encode($section->extra, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE) : '') }}</textarea>
<input type="checkbox" name="is_active" value="1" {{ old('is_active', $section->is_active ?? true) ? 'checked' : '' }}>
