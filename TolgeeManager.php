<?php


use Tolgee\Core\Enums\Modes;
use Tolgee\Core\Tolgee;
use Tolgee\Core\TolgeeConfig;

class TolgeeManager
{
    private $tolgee;
    private $config;
    private $lang;

    public function __construct($lang)
    {
        $this->lang = $lang;
    }

    public function getTolgee(): Tolgee
    {
        if ($this->tolgee === null) {
            $this->tolgee = new Tolgee($this->getConfig());
        }
        $this->tolgee->setCurrentLang($this->lang);
        return $this->tolgee;
    }

    public function getConfig(): TolgeeConfig
    {
        {
            if ($this->config === null) {
                $this->config = new TolgeeConfig();
                $this->config->apiKey = $_ENV["TOLGEE_API_KEY"];
                $this->config->apiUrl = $_ENV["TOLGEE_API_URL"];
                $this->config->mode = $_ENV["TOLGEE_API_KEY"] ? Modes::DEVELOPMENT : Modes::PRODUCTION;
                $this->config->localFilesAbsolutePath = __DIR__ . "/i18n";
            }
            return $this->config;
        }
    }

    public function getTolgeeFrontendPart(): string
    {
        if ($this->getConfig()->mode !== Modes::DEVELOPMENT) {
            return '';
        }
        return '
<div id="loading" style="position: fixed; top:0; left: 0; width: 100%; height: 100%;
            background-color: white; display: flex; align-items: center; justify-content: center;">
    Loading...
</div>
<script src="node_modules/@tolgee/core/dist/tolgee.window.js"></script>
<script src="node_modules/@tolgee/ui/dist/tolgee.window.js"></script>
<script>
    const tg = new window["@tolgee/core"].Tolgee({
        apiUrl: "' . $this->getConfig()->apiUrl . '",
        apiKey: "' . $this->getConfig()->apiKey . '",
        ui: window["@tolgee/ui"].UI,
        tagAttributes: {
            "textarea": ["placeholder"],
            "input": ["value", "placeholder"],
            "select": ["aria-label"]
        }
    });

    tg.lang = "' . $this->lang . '"
    tg.run().then(() => {
        document.getElementById("loading").style.display = "none";
    })
</script>';
    }
}