//
//  MessageTableViewController.m
//  MazuMarchAdmin
//
//  Created by OA Wu on 2016/3/28.
//  Copyright © 2016年 OA Wu. All rights reserved.
//

#import "MessageTableViewController.h"

@interface MessageTableViewController ()

@end

@implementation MessageTableViewController

- (void)viewDidLoad {
    [super viewDidLoad];

    [self.tableView setSeparatorStyle:UITableViewCellSeparatorStyleSingleLine];
    [self.tableView setSeparatorInset:UIEdgeInsetsMake(0, 65, 0, 0)];
    
    self.refreshControl = [UIRefreshControl new];
    [self.refreshControl addTarget:self action:@selector(refreshAction) forControlEvents:UIControlEventValueChanged];
    
    UIBarButtonItem *anotherButton = [[UIBarButtonItem alloc] initWithTitle:@"＋" style:UIBarButtonItemStylePlain target:self action:@selector(refreshPropertyList)];
    [anotherButton setTintColor:[UIColor colorWithRed:1.00 green:1.00 blue:1.00 alpha:1.00]];
    self.navigationItem.rightBarButtonItem = anotherButton;
}
-(UIStatusBarStyle)preferredStatusBarStyle {
    return UIStatusBarStyleLightContent;
}
- (BOOL)prefersStatusBarHidden {
    return NO;
}
- (void)refreshPropertyList {
    UIAlertController *inputAlert = [UIAlertController
                                alertControllerWithTitle:@"輸入訊息"
                                message:nil
                                preferredStyle:UIAlertControllerStyleAlert];
    
    UIAlertAction *okAction = [UIAlertAction
                                 actionWithTitle:@"確定"
                                 style:UIAlertActionStyleDefault
                               handler:^(UIAlertAction * action) {
                                   [self sendMessage: ((UITextField *)[inputAlert.textFields objectAtIndex:0]).text];
                               }];
    UIAlertAction *cancelAction = [UIAlertAction
                               actionWithTitle:@"取消"
                               style:UIAlertActionStyleDefault
                               handler:nil];
    
    [inputAlert addTextFieldWithConfigurationHandler:^(UITextField *text){
        [text setPlaceholder:@"請輸入管理員公告訊息.."];
    }];
    [inputAlert addAction:okAction];
    [inputAlert addAction:cancelAction];
    
    [self.parentViewController presentViewController:inputAlert animated:YES completion:nil];
}
-(void)sendMessage:(NSString *) message {
    
    NSMutableDictionary *data = [NSMutableDictionary new];
    [data setValue:message forKey:@"msg"];
    [data setValue:USER_ID forKey:@"user_id"];
    
    AFHTTPRequestOperationManager *httpManager = [AFHTTPRequestOperationManager manager];
    [httpManager.responseSerializer setAcceptableContentTypes:[NSSet setWithObject:@"application/json"]];
    [httpManager POST:SEND_MESSAGE_API_URL
           parameters:data
              success:^(AFHTTPRequestOperation *operation, id responseObject) {
                  [self loadData:nil];
              }
              failure:^(AFHTTPRequestOperation *operation, NSError *error) {
                  [self loadData:nil];
              }
     ];
    
}
- (void)clean {
    self.maxId = 0;
    self.isLoading = YES;
    self.messages = [NSMutableArray new];
    
    if (self.timer) {
        [self.timer invalidate];
        self.timer = nil;
    }
    [self.tableView reloadData];
}

- (void)refreshAction {
    [self.refreshControl endRefreshing];

    [self clean];
    
    [self reloadData];
}
- (void)reloadData {
    UIAlertController *alert = [UIAlertController alertControllerWithTitle:@"取得資料中" message:@"請稍候..." preferredStyle:UIAlertControllerStyleAlert];
    
    [self.parentViewController presentViewController:alert animated:YES completion:^{
        self.maxId = 0;
        self.isLoading = NO;
        self.messages = [NSMutableArray new];
        [self loadData:alert];
        self.timer = [NSTimer scheduledTimerWithTimeInterval:LOAD_MESSAGE_TIMER target:self selector:@selector(loadDataByTimer) userInfo:nil repeats:YES];
    }];
}
- (void)viewWillAppear:(BOOL)animated {
    [super viewWillAppear:animated];
    [self reloadData];
}

- (void)viewDidDisappear:(BOOL)animated {
    [super viewDidDisappear:animated];
    [self clean];
}
- (void)loadDataByTimer {
    [self loadData: nil];
}
- (void)loadData:(UIAlertController *)alert {
    if (self.isLoading) {
        if (alert) [alert dismissViewControllerAnimated:YES completion:nil];
        return;
    }
    
    self.isLoading = YES;

    AFHTTPRequestOperationManager *httpManager = [AFHTTPRequestOperationManager manager];
    [httpManager.requestSerializer setCachePolicy:NSURLRequestReloadIgnoringLocalCacheData];
    [httpManager.responseSerializer setAcceptableContentTypes:[NSSet setWithObject:@"text/plain"]];
    [httpManager GET:LOAD_MESSAGE_API_URL
          parameters:nil
             success:^(AFHTTPRequestOperation *operation, id responseObject) {
                 self.isLoading = NO;

                 if (![[responseObject objectForKey:@"s"] boolValue]) {
                     if (alert) [alert dismissViewControllerAnimated:YES completion:nil];
                     return;
                 }

                 int i = 0;
                 for (NSDictionary *msg in [responseObject objectForKey:@"m"])
                     if (self.maxId == 0)
                         [self.messages addObject: [[Message alloc] initWithDictionary: msg]];
                     else if ([[msg objectForKey:@"d"] integerValue] > self.maxId)
                         [self.messages insertObject: [[Message alloc] initWithDictionary: msg] atIndex: i++];
                 

                 self.maxId = (int)[[self.messages firstObject].id integerValue];
                 
                 [self.tableView reloadData];

                 if (alert) [alert dismissViewControllerAnimated:YES completion:nil];
             }
             failure:^(AFHTTPRequestOperation *operation, NSError *error) {
                 self.isLoading = NO;
                 if (alert) [alert dismissViewControllerAnimated:YES completion:nil];
//                 NSLog(@"=======>Failure!Error:%@", [[NSString alloc] initWithData:(NSData *)error.userInfo[AFNetworkingOperationFailingURLResponseDataErrorKey] encoding:NSUTF8StringEncoding]);
             }
     ];
}

- (void)didReceiveMemoryWarning {
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}

#pragma mark - Table view data source

- (NSInteger)numberOfSectionsInTableView:(UITableView *)tableView {
    
    return 1;
}

- (NSInteger)tableView:(UITableView *)tableView numberOfRowsInSection:(NSInteger)section {
    return [self.messages count];
}

- (CGFloat)tableView:(UITableView *)tableView heightForRowAtIndexPath:(NSIndexPath *)indexPath {
    return 100;
}
- (BOOL)tableView:(UITableView *)tableView canEditRowAtIndexPath:(NSIndexPath *)indexPath {
    return ![self.messages objectAtIndex:indexPath.row].isAdmin;
}
-(void)tableView:(UITableView *)tableView commitEditingStyle:(UITableViewCellEditingStyle)editingStyle forRowAtIndexPath:(NSIndexPath *)indexPath
{
    [self.messages removeObjectAtIndex:indexPath.row];
    [tableView deleteRowsAtIndexPaths:[NSArray arrayWithObject:indexPath] withRowAnimation:UITableViewRowAnimationTop];
//    ;
    [self toBlock:[self.messages objectAtIndex:indexPath.row].ip];
}
- (void)toBlock: (NSString *) ip {
    
}
-(NSString *)tableView:(UITableView *)tableView titleForDeleteConfirmationButtonForRowAtIndexPath:(NSIndexPath *)indexPath {
    return @"黑名單";
}
- (UITableViewCell *)tableView:(UITableView *)tableView cellForRowAtIndexPath:(NSIndexPath *)indexPath {

    NSString *identifier = [NSString stringWithFormat:@"MessageCell_%@", [self.messages objectAtIndex:indexPath.row].id];
    MessageTableViewCell *cell = (MessageTableViewCell *)[tableView dequeueReusableCellWithIdentifier:identifier];
    
    if(cell == nil) {
        cell = [[MessageTableViewCell alloc] initCellWithStyle: [self.messages objectAtIndex:indexPath.row] style:UITableViewCellStyleDefault reuseIdentifier:identifier];
    }
    
    return cell;
}


/*
// Override to support conditional editing of the table view.
- (BOOL)tableView:(UITableView *)tableView canEditRowAtIndexPath:(NSIndexPath *)indexPath {
    // Return NO if you do not want the specified item to be editable.
    return YES;
}
*/

/*
// Override to support editing the table view.
- (void)tableView:(UITableView *)tableView commitEditingStyle:(UITableViewCellEditingStyle)editingStyle forRowAtIndexPath:(NSIndexPath *)indexPath {
    if (editingStyle == UITableViewCellEditingStyleDelete) {
        // Delete the row from the data source
        [tableView deleteRowsAtIndexPaths:@[indexPath] withRowAnimation:UITableViewRowAnimationFade];
    } else if (editingStyle == UITableViewCellEditingStyleInsert) {
        // Create a new instance of the appropriate class, insert it into the array, and add a new row to the table view
    }   
}
*/

/*
// Override to support rearranging the table view.
- (void)tableView:(UITableView *)tableView moveRowAtIndexPath:(NSIndexPath *)fromIndexPath toIndexPath:(NSIndexPath *)toIndexPath {
}
*/

/*
// Override to support conditional rearranging of the table view.
- (BOOL)tableView:(UITableView *)tableView canMoveRowAtIndexPath:(NSIndexPath *)indexPath {
    // Return NO if you do not want the item to be re-orderable.
    return YES;
}
*/

/*
#pragma mark - Navigation

// In a storyboard-based application, you will often want to do a little preparation before navigation
- (void)prepareForSegue:(UIStoryboardSegue *)segue sender:(id)sender {
    // Get the new view controller using [segue destinationViewController].
    // Pass the selected object to the new view controller.
}
*/

@end
