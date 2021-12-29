<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class createParser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:parser {Website Name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command help you to create new standar parser';

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
     * @return int
     */
    public function handle()
    {
        $parser_name = strtolower($this->argument('Website Name')) . 'Parser';
        $namafile = app_path('Parsers/'.$parser_name.".php");

        $fh = fopen($namafile,"w");  
        $standar_parser = file_get_contents(base_path('vendor/abdelrahmanmedhat/blogsscraper/src/standar.parser'));
        $standar_parser = str_replace('{@{Parser_Name}@}',$parser_name,$standar_parser);
        fwrite($fh,$standar_parser);
        fclose($fh);  

        $this->info('');
        $this->info('▁ ▂ ▄ ▅ ▆ ▇ █ 𝙱𝚕𝚘𝚐𝚜-𝚂𝚌𝚛𝚊𝚙𝚎𝚛 𝙱𝚢 𝙰𝚋𝚍𝚎𝚕𝚛𝚊𝚑𝚖𝚊𝚗 𝙼𝚎𝚍𝚑𝚊𝚝 █ ▇ ▆ ▅ ▄ ▂ ▁');
        $this->info('');
        $this->info('                 𝙋𝙖𝙧𝙨𝙚𝙧 𝘾𝙧𝙚𝙖𝙩𝙚𝙙 𝙎𝙪𝙘𝙘𝙚𝙨𝙨𝙛𝙪𝙡𝙡𝙮          ');
        $this->info('');
        return 0;
    }
}
