<?php

class WPFirstCest
{
    public function _before(AcceptanceTester $I)
    {
    }

    // tests
    public function tryToTest(AcceptanceTester $I)
    {
        $I->wantTo('Test that we have working tests');
        $I->amOnPage('/');
        $I->seeElement('body');
    }

    public function tryToActivatePlugin(AcceptanceTester $I) {
        $I->wantTo('Test that we can activate Electro plugin.');
        $I->loginAsAdmin();
        $I->amOnPluginsPage();
        $I->seePluginDeactivated('electro');
    }


}
