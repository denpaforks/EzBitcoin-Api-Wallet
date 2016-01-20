<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class ApiBlocknotify extends Command {

        /**
         * The console command name.
         *
         * @var string
         */
        protected $name = 'api:blocknotify';

        /**
         * The console command description.
         *
         * @var string
         */
        protected $description = 'Call blocknotify method from CLI.';

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
                $request = Request::create('/api/blocknotify', 'GET', array(
                        'secret' => !$this->option('secret') ? Config::get('bitcoin.callback_secret') : $this->option('secret'),
                        'blockhash' => $this->option('blockhash'),
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
                        array('blockhash', null, InputOption::VALUE_REQUIRED, 'Hash of new best block.', null),
						array('secret', null, InputOption::VALUE_OPTIONAL, 'Callback secret.', null),
						array('debug', null, InputOption::VALUE_OPTIONAL, 'Show output.', null),
                );
        }
}