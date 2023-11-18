## Find DOctor v2.0 - 08/12/2019 - CodeIgniter 3.10
# Utiliza como submodulo
Para utilizar como submodulo este repositorio
luego de clonar/crear un proyecto debe iniciarse de la siguiente manera:

Usando ssh:
```
git submodule add git@gitlab.com:sstarteam/sses6.git assets/js/solstar
```


```
# Luego de clonar

# para inicializar el archivo de configuración local
git submodule init

# para recuperar (fetch) todos los datos del proyecto y extraer (checkout) la confirmación de cambios adecuada desde el proyecto padre
git submodule update
```

# Actualizar cache de git
```
git rm -r --cached .
git add .
git commit -m "Actualizando .gitignore"
git push
```
## License

Soluciones Star SAS is under the [MIT License](LICENSE).
