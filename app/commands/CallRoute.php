<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class CallRoute extends Command {

        /**
         * The console command name.
         *
         * @var string
         */
        protected $name = 'route:call';

        /**
         * The console command description.
         *
         * @var string
         */
        protected $description = 'Call laravel from shell.';

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
				$cli_methods = array('/api/callback', '/api/blocknotify');
				
				// Normalize URI a little bit
				$uri = '/' . ltrim( $this->option('uri'), '/' );
				
				if( !in_array($uri, $cli_methods ) ) {
					$this->error('Method ' . $uri . ' is not allowed from CLI');
					return;
				}
			
                $request = Request::create($uri, 'GET', array(
                        'secret' => $this->option('secret'),
                        'txid' => $this->option('txid'),
                ));
                Request::replace( $request->input() );
                $this->info( Route::dispatch($request)->getContent() );
        }

        /**
         * Get the console command options.
         *
         * @return array
         */
        protected function getOptions()
        {
                return array(
                        array('uri', null, InputOption::VALUE_REQUIRED, 'Route path.', null),
                        array('secret', null, InputOption::VALUE_REQUIRED, 'Callback secret.', null),
                        array('txid', null, InputOption::VALUE_REQUIRED, 'Transaction ID.', null),
                );
        }
}