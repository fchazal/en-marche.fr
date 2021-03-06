# Styleguide du contenu

## Typographie
Le titre de l'article sera interpreté en tant que `h1`.
Le sous-titre de l'article sera interpreté en tant que `h2`.

Donc les titres à l'interieur de l'article devront utiliser le markup de `h3`, soit `###`.

```
### Ceci est un titre à l'interieur d'un article
```

## Images

Par default, l'image principale de l'article s'afichera en full width. Si votre image n'est pas
d'assez bonne qualité ou sa largeur < 1200px, ajoutez la dans le contenu de l'article, et utilisez les displays suivants:   

Image centrée (sa largeur est < 725px):
```
<figure>
    <img src="http://s1.lemde.fr/image/2016/01/06/534x0/4842412_7_2601_emmanuel-macron-ministre-de-l-economie-a_afcfb3fd9194bc3763e16b9682dbe111.jpg">
</figure>
```

Image aussi large que le reste de l'article (sa largeur doit être > 725px):
```
<figure>
    <img class"large" src="http://i.f1g.fr/media/figaro/1280x580_crop/2016/04/08/XVMe0a5d610-fd5d-11e5-bf72-58d0fa6caeec.jpg">
</figure>
```

Ajouter une legende:
```
<figure>
    <img src="http://i.f1g.fr/media/figaro/1280x580_crop/2016/04/08/XVMe0a5d610-fd5d-11e5-bf72-58d0fa6caeec.jpg">
    <figcaption>Ceci est une legende.</figcaption>
</figure>
```


## Videos

Les iframes de vidéos doivent être entourées de la classe `.video`.

```
<div class="video">
    <iframe width="560" height="315" src="https://www.youtube-nocookie.com/embed/DKimfuAWNvQ?rel=0" frameborder="0" allowfullscreen></iframe>
</div>
```

## Citations

Entourez les citations des tags `figure`, `blockquote` et `span`.
Si vous voulez l'attribuer un `figcaption` à l'interieur de la `figure`.

```
<figure>
    <blockquote>
        <span>«Tout commence par la rénovation de l’engagement politique.»</span>
    </blockquote>
    <figcaption>Emmanuel Macron, le 12 juillet 2016</figcaption>
</figure>
```
