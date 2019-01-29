<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->call('AddressTableSeeder');
        $this->call('BankTableSeeder');
        $this->call('BrandTableSeeder');
        $this->call('CategoryTableSeeder');
        $this->call('ChatTableSeeder');
        $this->call('ContactTableSeeder');
        $this->call('ConversationTableSeeder');
        $this->call('DiscountcouponhistoryTableSeeder');
        $this->call('ExternalLinkTableSeeder');
        $this->call('FreesampleTableSeeder');
        $this->call('MenuTableSeeder');
        $this->call('OrderdetailhistoryTableSeeder');
        $this->call('OrderheaderhistoryTableSeeder');
        $this->call('OrdermarketplaceTableSeeder');
        $this->call('PackingfeeTableSeeder');
        $this->call('PriceTableSeeder');
        $this->call('PriceProcessTableSeeder');
        $this->call('PointTableSeeder');
        $this->call('PointconfigTableSeeder');
        $this->call('ProductTableSeeder');
        $this->call('ProductimageTableSeeder');
        $this->call('ProductsetTableSeeder');
        $this->call('RefundrequestTableSeeder');
        $this->call('RefundrequestdetailTableSeeder');
        $this->call('RefundstatusTableSeeder');
        $this->call('ReservedstockhistoryTableSeeder');
        $this->call('ShopeeSalesTableSeeder');
        $this->call('StockbalanceTableSeeder');
        $this->call('StockinTableSeeder');
        $this->call('StockreviseTableSeeder');
        $this->call('StocktransferTableSeeder');
        $this->call('SubcategoryTableSeeder');
        $this->call('SubmenuTableSeeder');
        $this->call('TableStatusTableSeeder');
        $this->call('TermTableSeeder');
        $this->call('UserMenuTableSeeder');
        $this->call('UserTableSeeder');
        $this->call('UsersettingTableSeeder');

        Model::reguard();
    }
}
