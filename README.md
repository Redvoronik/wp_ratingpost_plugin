# Рейтинги статей

<h3>Для отправки оценки:</h3>
/wp-json/rating/set?post_id=123&value=positive&comment=testtesttest
<ul>
<li><code>post_id</code> - id статьи</li>
<li><code>value</code> - тип оценки positive/negative</li>
<li><code>comment</code> - комментарий к оценке</li>
</ul>

<h3>Для получения оценок:</h3>
/wp-json/rating/get?post_id=123
<ul>
<li><code>post_id</code> - id статьи</li>
</ul>
Данные придут в json: positive: N, negative: N
