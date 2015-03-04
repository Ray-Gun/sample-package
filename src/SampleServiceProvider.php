<?php namespace raygund\SamplePackage;

use Illuminate\Support\ServiceProvider;

class SampleServiceProvider extends ServiceProvider
{
    //--name of the package
    protected $packageName = 'sample';
    //--file names
    protected $configFileName = 'config.php';
    protected $routeFileName  = 'routes.php';
    //--paths to files
    protected $pathToConfig = '/../config/';
    protected $pathToRoutes = '/../resources/';
    //--paths to dirs
    protected $pathToAssets = '/../resources/assets';
    protected $pathToLang   = '/../resources/lang';
    protected $pathToPublic = '/../resources/public';
    protected $pathToViews  = '/../resources/views';
    //--standard publish tags
    protected $config = 'config';
    protected $views  = 'views';
    protected $public = 'public';
    protected $assets = 'assets';
    protected $lang   = 'lang';

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->includeRoutes();

        $this->loadViewsFrom($this->viewPath(), $this->packageName);
        $this->loadTranslationsFrom($this->pathToLang, $this->packageName);

        //--publish config
        $this->publishes($this->configPublishPaths(), $this->config);
        //--publish views
        $this->publishes($this->viewPublishPaths(), $this->views);
        //--publish public resources
        $this->publishes($this->publicPublishPaths(), $this->public);
        //--publish assets
        $this->publishes($this->assetsPublishPaths(), $this->assets);
        //--publish translations
        $this->langPublish();
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom($this->configPath(), $this->packageName);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['sample.service.provider'];
    }

    //---------------------------------------------------------------------------------------------

    protected function configPath()
    {
        return __DIR__.$this->pathToConfig.$this->configFileName;
    }

    protected function configPublishPaths()
    {
        return [$this->configPath() => config_path($this->configFileName)];
    }

    protected function includeRoutes()
    {
        include __DIR__.$this->pathToRoutes.$this->routeFileName;
    }

    protected function viewPath()
    {
        return __DIR__.$this->pathToViews;
    }

    protected function viewPublishPaths()
    {
        return [$this->viewPath() => base_path('resources/views/vendor/'.$this->packageName)];
    }

    protected function publicPublishPaths()
    {
        return [__DIR__.$this->pathToPublic => public_path('vendor/'.$this->packageName)];
    }

    protected function assetsPublishPaths()
    {
        return [__DIR__.$this->pathToAssets => base_path('resources/assets/vendor/'.$this->packageName)];
    }

    protected function langPublishPaths($local)
    {
        return [__DIR__.$this->pathToLang.'/'.$local =>
            base_path('resources/lang/packages/'.$local.'/'.$this->packageName)];
    }

    protected function langPublish()
    {
        foreach (\File::directories(__DIR__ . $this->pathToLang) as $locale) {
            $this->publishes($this->langPublishPaths($locale), $locale);
        }
    }
}
