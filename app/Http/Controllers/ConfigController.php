<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;



class ConfigController extends Controller
{
    public function setDatabaseConfig(Request $request)
    {
        $driver = $request->input('driver');
        $host = $request->input('host');
        $database = $request->input('database');
        $user = $request->input('user');
        $password = $request->input('password');



        // Update the configuration dynamically based on the selected driver
        switch ($driver) {
            case 'mysql':
                Config::set('database.connections.mysql.host', $host);
                Config::set('database.connections.mysql.database', $database);
                Config::set('database.connections.mysql.username', $user);
                Config::set('database.connections.mysql.password', $password);
                break;

            case 'pgsql':
                Config::set('database.connections.pgsql.host', $host);
                Config::set('database.connections.pgsql.database', $database);
                Config::set('database.connections.pgsql.username', $user);
                Config::set('database.connections.pgsql.password', $password);
                break;

            // Add other cases for different drivers as needed

            default:
                return response()->json(['error' => 'Unsupported database driver']);
        }

        Config::set('database.default', $driver);

        return response()->json(['message' => 'Configuration updated successfully']);
    }
	
	

		public function getTableNames()
		{
			try {
				// Use the default database connection
				$tables = DB::select('SHOW TABLES');

				return response()->json(['tables' => array_column($tables, 'Tables_in_' . config('database.connections.' . config('database.default') . '.database'))]);
			} catch (\Exception $e) {
				return response()->json(['error' => 'Database not found.']);
			}
		}
		
		
	public function executeQuery(Request $request)
    {
        $query = $request->input('query');

        try {
            // Execute the query
            $result = DB::select($query);

            return response()->json(['data' => $result]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }

}
