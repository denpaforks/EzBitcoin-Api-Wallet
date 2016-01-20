<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class ApiCallback extends Command {

        /**
         * The console command name.
         *
         * @var string
         */
        protected $name = 'api:callback';

        /**
         * The console command description.
         *
         * @var string
         */
        protected $description = 'Call callback method from CLI.';

        /**
         * Create a new command instance.
         *
         * @return void
         */
        public function __construct()
        {
                parent::__construct();
        }

        /**
         * Execute the console command.
         *
         * @return mixed
         */
        public function fire()
        {
                $request = Request::create('/api/callback', 'GET', array(
                        'secret' => !$this->option('secret') ? Config::get('bitcoin.callback_secret') : $this->option('secret'),
                        'txid' => $this->option('txid'),
                ));
                Request::replace( $request->input() );

				$response = Route::dispatch($request)->getContent();
                if( $this->option('debug') ) {
					$this->info($response);
				}
        }

        /**
         * Get the console command options.
         *
         * @return array
         */
        protected function getOptions()
        {
                return array(
                        array('txid', null, InputOption::VALUE_REQUIRED, 'Transaction ID.', null),
						array('secret', null, InputOption::VALUE_OPTIONAL, 'Callback secret.', null),
						array('debug', null, InputOption::VALUE_OPTIONAL, 'Show output.', null),
                );
        }
}