# affichage du rating

## solution if else

```php
<div class="d-flex" style="color: orange;">
    {% if movie.rating >= 1 %}<i class="bi bi-star-fill"></i>{% elseif movie.rating >= 0.5 %}<i class="bi bi-star-half"></i>{% else %}<i class="bi bi-star"></i>{% endif %}
    {% if movie.rating >= 2 %}<i class="bi bi-star-fill"></i>{% elseif movie.rating >= 1.5 %}<i class="bi bi-star-half"></i>{% else %}<i class="bi bi-star"></i>{% endif %}
    {% if movie.rating >= 3 %}<i class="bi bi-star-fill"></i>{% elseif movie.rating >= 2.5 %}<i class="bi bi-star-half"></i>{% else %}<i class="bi bi-star"></i>{% endif %}
    {% if movie.rating >= 4 %}<i class="bi bi-star-fill"></i>{% elseif movie.rating >= 3.5 %}<i class="bi bi-star-half"></i>{% else %}<i class="bi bi-star"></i>{% endif %}
    {% if movie.rating >= 5 %}<i class="bi bi-star-fill"></i>{% elseif movie.rating >= 4.5 %}<i class="bi bi-star-half"></i>{% else %}<i class="bi bi-star"></i>{% endif %}
    <span class="ps-1">{{ movie.rating }}</span>
</div>
```

## solution avec for et variable

```php
<div class="d-flex" style="color: orange;">
    {% for i in 0..4 %}
        {# je stocke le résultat du if dans une variable pour que ça soit plus lisible dans la partie html #}
        {% set starClass = movie.rating - i >= 1 ? '-fill' : (movie.rating - i >= 0.5 ? '-half' : '') %}
        <span class="bi bi-star{{ starClass }}"></span>
    {% endfor %}
        <span class="ps-1">{{ movie.rating }}</span>
</div>
```

## solution avec for et variable qui gère diférement les demi étoiles

il suffit de changer les valeurs bornes du if

```php
<div class="d-flex" style="color: orange;">
    {% for i in 0..4 %}
        {# je stocke le résultat du if dans une variable pour que ça soit plus lisible dans la partie html #}
        {% set starClass = movie.rating - i >= 0.75 ? '-fill' : (movie.rating - i >= 0.25 ? '-half' : '') %}
        <span class="bi bi-star{{ starClass }}"></span>
    {% endfor %}
        <span class="ps-1">{{ movie.rating }}</span>
</div>
```

## solution qui n'affiche pas les étoiles vides

```php
<div class="d-flex" style="color: orange;">
    {% set k = movie.rating %}
    {% for i in range(1, movie.rating|round(0, 'floor')) %}
        <i class="bi bi-star-fill"></i>
        {% set k = k-1 %}
    {% endfor %}
    {% if k>0 %}
        <i class="bi bi-star-half"></i>
    {% endif %}
    <span class="ps-1">{{ movie.rating }}</span>
</div>
```